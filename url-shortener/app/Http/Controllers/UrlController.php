<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class UrlController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $user = $request->user();

        abort_if($user->isSuperAdmin(), 403, 'Super admins cannot browse short URLs.');

        $query = Url::query()->with(['creator', 'company']);

        if ($user->isAdmin()) {
            $query->where('company_id', '!=', $user->company_id);
        } elseif ($user->isMember()) {
            $query->where('user_id', '!=', $user->id);
        } else {
            $query->where('company_id', $user->company_id);
        }

        $urls = $query->latest()->get();

        if ($request->wantsJson()) {
            return response()->json($urls);
        }

        return view('urls.index', compact('urls'));
    }

    public function create(Request $request): View
    {
        abort_unless($request->user()->canCreateUrls(), 403, 'You are not allowed to create short URLs.');

        return view('urls.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->canCreateUrls(), 403, 'You are not allowed to create short URLs.');

        $validated = $request->validate([
            'destination' => ['required', 'url'],
        ]);

        abort_if(is_null($user->company_id), 403, 'A company must be assigned before creating URLs.');

        $slug = $this->generateSlug();

        Url::create([
            'company_id' => $user->company_id,
            'user_id' => $user->id,
            'slug' => $slug,
            'destination' => $validated['destination'],
        ]);

        return redirect()->route('urls.index')->with('status', 'Short URL created: '.$slug);
    }

    private function generateSlug(): string
    {
        do {
            $slug = Str::lower(Str::random(8));
        } while (Url::where('slug', $slug)->exists());

        return $slug;
    }
}
