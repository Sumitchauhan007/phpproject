<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Url;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        $companies = collect();
        $urls = collect();

        if ($user->isSuperAdmin()) {
            $companies = Company::query()
                ->withCount('users')
                ->orderBy('name')
                ->get();
        } elseif ($user->isAdmin()) {
            $urls = Url::query()
                ->with(['company', 'creator'])
                ->where('company_id', $user->company_id)
                ->latest()
                ->limit(10)
                ->get();
        } elseif ($user->isMember()) {
            $urls = Url::query()
                ->with(['company', 'creator'])
                ->where('user_id', $user->id)
                ->latest()
                ->limit(10)
                ->get();
        } else {
            $urls = Url::query()
                ->with(['company', 'creator'])
                ->where('company_id', $user->company_id)
                ->latest()
                ->limit(10)
                ->get();
        }

        return view('dashboard', compact('user', 'companies', 'urls'));
    }
}
