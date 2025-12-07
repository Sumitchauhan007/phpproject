<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Url;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlPermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_create_short_urls(): void
    {
        $company = Company::factory()->create();
        $admin = User::factory()->for($company)->admin()->create();

        $this->actingAs($admin)
            ->post(route('urls.store'), ['destination' => 'https://example.com'])
            ->assertForbidden();

        $this->assertDatabaseCount('urls', 0);
    }

    public function test_member_cannot_create_short_urls(): void
    {
        $company = Company::factory()->create();
        $member = User::factory()->for($company)->member()->create();

        $this->actingAs($member)
            ->post(route('urls.store'), ['destination' => 'https://example.com'])
            ->assertForbidden();

        $this->assertDatabaseCount('urls', 0);
    }

    public function test_super_admin_cannot_create_short_urls(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)
            ->post(route('urls.store'), ['destination' => 'https://example.com'])
            ->assertForbidden();

        $this->assertDatabaseCount('urls', 0);
    }

    public function test_admin_sees_urls_not_in_their_company(): void
    {
        $companyA = Company::factory()->create(['name' => 'Company A']);
        $companyB = Company::factory()->create(['name' => 'Company B']);

        $admin = User::factory()->for($companyA)->admin()->create();

        // URL from same company (should be hidden)
        Url::factory()->for($companyA)->for($admin, 'creator')->create(['destination' => 'https://company-a.com']);

        // URL from another company (should be visible)
        $visibleUrl = Url::factory()
            ->for($companyB)
            ->for(User::factory()->for($companyB)->sales(), 'creator')
            ->create(['destination' => 'https://company-b.com']);

        $response = $this->actingAs($admin)->get(route('urls.index'));

        $response->assertOk();
        $urls = $response->viewData('urls');
        $this->assertTrue($urls->contains('id', $visibleUrl->id));
        $this->assertFalse($urls->contains(fn ($item) => $item->company_id === $companyA->id));
    }

    public function test_member_sees_urls_not_created_by_them(): void
    {
        $company = Company::factory()->create();
        $member = User::factory()->for($company)->member()->create();

        // URL created by the member (should be hidden)
        Url::factory()->for($company)->for($member, 'creator')->create();

        // URL created by someone else (should be listed)
        $otherUser = User::factory()->for($company)->sales()->create();
        $visibleUrl = Url::factory()->for($company)->for($otherUser, 'creator')->create();

        $response = $this->actingAs($member)->get(route('urls.index'));

        $response->assertOk();
        $urls = $response->viewData('urls');
        $this->assertTrue($urls->contains('id', $visibleUrl->id));
        $this->assertFalse($urls->contains(fn ($item) => $item->user_id === $member->id));
    }

    public function test_short_urls_require_authentication_and_redirect_after_login(): void
    {
        $company = Company::factory()->create();
        $creator = User::factory()->for($company)->sales()->create();
        $url = Url::factory()->for($company)->for($creator, 'creator')->create([
            'destination' => 'https://laravel.com',
        ]);

        $this->get(route('short-url.show', ['slug' => $url->slug]))->assertRedirect(route('login'));

        $this->actingAs($creator)
            ->get(route('short-url.show', ['slug' => $url->slug]))
            ->assertRedirect($url->destination);
    }
}
