<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Company;
use App\Models\Invitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InvitationController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $invitations = Invitation::query()
            ->with(['inviter', 'company'])
            ->when(! $user->isSuperAdmin(), fn ($query) => $query->where('company_id', $user->company_id))
            ->latest()
            ->get();

        return view('invitations.index', [
            'invitations' => $invitations,
            'user' => $user,
        ]);
    }

    public function create(Request $request): View
    {
        $user = $request->user();

        abort_unless($user->isSuperAdmin() || $user->isAdmin(), 403);

        $roles = Role::all();
        if ($user->isAdmin()) {
            $roles = array_values(array_filter($roles, fn (string $role) => $role !== Role::SUPER_ADMIN));
        }

        return view('invitations.create', [
            'roles' => $roles,
            'companies' => Company::orderBy('name')->get(),
            'user' => $user,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user->isSuperAdmin() || $user->isAdmin(), 403);

        $roles = Role::all();

        $rules = [
            'email' => ['required', 'email'],
            'role' => ['required', Rule::in($roles)],
            'company_id' => ['nullable', 'exists:companies,id'],
        ];

        if ($user->isAdmin()) {
            $request->merge(['company_id' => $user->company_id]);
        }

        $data = $request->validate($rules);

        if ($user->isAdmin()) {
            if (in_array($data['role'], [Role::ADMIN, Role::MEMBER], true)) {
                return back()->withErrors([
                    'role' => 'Admins may only invite Sales or Manager roles.',
                ])->withInput();
            }

            $data['company_id'] = $user->company_id;
        }

        if ($user->isSuperAdmin()) {
            if ($data['role'] === Role::SUPER_ADMIN) {
                $data['company_id'] = null;
            } else {
                if (empty($data['company_id'])) {
                    return back()->withErrors([
                        'company_id' => 'Please choose a company for this invitation.',
                    ])->withInput();
                }
            }
        }

        $token = (string) Str::uuid();

        Invitation::updateOrCreate(
            [
                'email' => $data['email'],
                'accepted_at' => null,
            ],
            [
                'invited_by' => $user->id,
                'company_id' => $data['company_id'] ?? null,
                'role' => $data['role'],
                'token' => $token,
                'accepted_at' => null,
            ]
        );

        return redirect()->route('invitations.index')->with('status', 'Invitation sent to '.$data['email']);
    }
}
