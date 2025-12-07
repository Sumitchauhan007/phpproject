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

    public function test_admin_can_create_short_urls(): void
    {
        $company = Company::factory()->create();
        $admin = User::factory()->for($company)->admin()->create();

        $response = $this->actingAs($admin)
            ->post(route('urls.store'), ['destination' => 'https://example.com']);

        $response->assertRedirect(route('urls.index'));

        $this->assertDatabaseHas('urls', [
            'company_id' => $company->id,
            'user_id' => $admin->id,
            'destination' => 'https://example.com',
        ]);
    }

    public function test_member_can_create_short_urls(): void
    {
        $company = Company::factory()->create();
        $member = User::factory()->for($company)->member()->create();

        $response = $this->actingAs($member)
            ->post(route('urls.store'), ['destination' => 'https://member.com']);

        $response->assertRedirect(route('urls.index'));

        $this->assertDatabaseHas('urls', [
            'company_id' => $company->id,
            'user_id' => $member->id,
            'destination' => 'https://member.com',
        ]);
    }

    public function test_super_admin_cannot_create_short_urls(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $this->actingAs($superAdmin)
            ->post(route('urls.store'), ['destination' => 'https://example.com'])
            ->assertForbidden();

        $this->assertDatabaseCount('urls', 0);
    }

    public function test_admin_sees_only_urls_from_their_company(): void
    {
        $companyA = Company::factory()->create(['name' => 'Company A']);
        $companyB = Company::factory()->create(['name' => 'Company B']);

        $admin = User::factory()->for($companyA)->admin()->create();

        $visibleUrl = Url::factory()
            ->for($companyA)
            ->for(User::factory()->for($companyA)->sales(), 'creator')
            ->create(['destination' => 'https://company-a.com']);

        Url::factory()
            ->for($companyB)
            ->for(User::factory()->for($companyB)->sales(), 'creator')
            ->create(['destination' => 'https://company-b.com']);

        $response = $this->actingAs($admin)->get(route('urls.index'));

        $response->assertOk();
        $urls = $response->viewData('urls');
        $this->assertTrue($urls->contains('id', $visibleUrl->id));
        $this->assertFalse($urls->contains(fn ($item) => $item->company_id !== $companyA->id));
    }

    public function test_member_sees_only_urls_they_created(): void
    {
        $company = Company::factory()->create();
        $member = User::factory()->for($company)->member()->create();

        $visibleUrl = Url::factory()->for($company)->for($member, 'creator')->create();

        $otherUrl = Url::factory()
            ->for($company)
            ->for(User::factory()->for($company)->sales(), 'creator')
            ->create();

        $response = $this->actingAs($member)->get(route('urls.index'));

        $response->assertOk();
        $urls = $response->viewData('urls');
        $this->assertTrue($urls->contains('id', $visibleUrl->id));
        $this->assertFalse($urls->contains(fn ($item) => $item->id === $otherUrl->id));
    }

    public function test_super_admin_sees_all_short_urls(): void
    {
        $superAdmin = User::factory()->superAdmin()->create();

        $companyA = Company::factory()->create();
        $companyB = Company::factory()->create();

        $urlA = Url::factory()->for($companyA)->for(User::factory()->for($companyA)->admin(), 'creator')->create();
        $urlB = Url::factory()->for($companyB)->for(User::factory()->for($companyB)->member(), 'creator')->create();

        $response = $this->actingAs($superAdmin)->get(route('urls.index'));

        $response->assertOk();
        $urls = $response->viewData('urls');
        $this->assertTrue($urls->contains('id', $urlA->id));
        $this->assertTrue($urls->contains('id', $urlB->id));
    }

    public function test_short_urls_are_publicly_resolvable(): void
    {
        $company = Company::factory()->create();
        $creator = User::factory()->for($company)->sales()->create();
        $url = Url::factory()->for($company)->for($creator, 'creator')->create([
            'destination' => 'https://laravel.com',
        ]);

        $this->get(route('short-url.show', ['slug' => $url->slug]))
            ->assertRedirect($url->destination);
    }
}
