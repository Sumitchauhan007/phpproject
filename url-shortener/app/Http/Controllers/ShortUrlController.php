<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShortUrlController extends Controller
{
    public function show(Request $request, string $slug): RedirectResponse
    {
        $url = Url::where('slug', $slug)->firstOrFail();

        $user = $request->user();

        $canVisit = false;

        if ($user->isAdmin()) {
            $canVisit = $url->company_id !== $user->company_id;
        } elseif ($user->isMember()) {
            $canVisit = $url->user_id !== $user->id;
        } elseif ($user->canCreateUrls()) {
            $canVisit = $url->company_id === $user->company_id;
        }

        abort_unless($canVisit, 403, 'You are not allowed to follow this short URL.');

        return redirect()->away($url->destination);
    }
}
