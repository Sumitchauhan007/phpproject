<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationRulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_must_choose_company_when_inviting_admin(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $response = $this->actingAs($superAdmin)
            ->from(route('invitations.create'))
            ->post(route('invitations.store'), [
                'email' => 'admin@example.com',
                'role' => Role::ADMIN,
                'company_id' => '',
            ]);

        $response->assertRedirect(route('invitations.create'));
        $response->assertSessionHasErrors('company_id');
    }

    public function test_admin_cannot_invite_admin_or_member(): void
    {
        $company = Company::factory()->create();
        $admin = User::factory()->for($company)->admin()->create();

        $this->actingAs($admin)
            ->from(route('invitations.create'))
            ->post(route('invitations.store'), [
                'email' => 'another-admin@example.com',
                'role' => Role::ADMIN,
            ])
            ->assertRedirect(route('invitations.create'))
            ->assertSessionHasErrors('role');

        $this->actingAs($admin)
            ->from(route('invitations.create'))
            ->post(route('invitations.store'), [
                'email' => 'member@example.com',
                'role' => Role::MEMBER,
            ])
            ->assertRedirect(route('invitations.create'))
            ->assertSessionHasErrors('role');

        $this->actingAs($admin)
            ->post(route('invitations.store'), [
                'email' => 'sales@example.com',
                'role' => Role::SALES,
            ])
            ->assertRedirect(route('invitations.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('invitations', [
            'email' => 'sales@example.com',
            'role' => Role::SALES,
            'company_id' => $company->id,
        ]);
    }

    public function test_non_admin_cannot_access_invitation_form(): void
    {
        $company = Company::factory()->create();
        $member = User::factory()->for($company)->member()->create();

        $this->actingAs($member)
            ->get(route('invitations.create'))
            ->assertForbidden();

        $this->actingAs($member)
            ->post(route('invitations.store'), [
                'email' => 'test@example.com',
                'role' => Role::SALES,
            ])
            ->assertForbidden();
    }
}
