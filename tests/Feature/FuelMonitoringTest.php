<?php

namespace Tests\Feature;

use App\Models\AdminAuditLog;
use App\Models\CrowdReport;
use App\Models\Station;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class FuelMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_homepage_loads_and_handles_empty_state(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('কোনো স্টেশন পাওয়া যায়নি');
    }

    public function test_admin_routes_require_authentication(): void
    {
        $response = $this->get('/admin/stations');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_create_station_with_fuel_status(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.stations.store'), [
            'name' => 'Mirpur Fuel Hub',
            'location' => 'Mirpur 10, Dhaka',
            'octane' => '1',
            'diesel' => '0',
        ]);

        $response->assertRedirect(route('admin.stations.index'));

        $this->assertDatabaseHas('stations', [
            'name' => 'Mirpur Fuel Hub',
            'location' => 'Mirpur 10, Dhaka',
        ]);

        $station = Station::first();

        $this->assertDatabaseHas('fuel_statuses', [
            'station_id' => $station->id,
            'octane' => 1,
            'diesel' => 0,
        ]);
    }

    public function test_api_returns_station_status_data(): void
    {
        $station = Station::factory()->create([
            'name' => 'Airport Fuel Service',
            'location' => 'Airport Road, Dhaka',
        ]);

        $station->fuelStatus()->create([
            'octane' => true,
            'diesel' => false,
        ]);

        $response = $this->getJson('/api/stations');

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.0.name', 'Airport Fuel Service');
        $response->assertJsonPath('data.0.fuel_status.octane', true);
        $response->assertJsonPath('data.0.fuel_status.diesel', false);
    }

    public function test_public_filters_can_limit_station_results(): void
    {
        $octaneOnly = Station::factory()->create(['name' => 'Octane Only', 'location' => 'Hathazari']);
        $octaneOnly->fuelStatus()->create(['octane' => true, 'diesel' => false]);

        $dieselOnly = Station::factory()->create(['name' => 'Diesel Only', 'location' => 'Hathazari']);
        $dieselOnly->fuelStatus()->create(['octane' => false, 'diesel' => true]);

        $response = $this->get('/?availability=octane');

        $response->assertOk();
        $response->assertSee('Octane Only');
        $response->assertDontSee('Diesel Only');
    }

    public function test_public_user_can_submit_crowd_feedback(): void
    {
        $station = Station::factory()->create([
            'name' => 'হাটহাজারী টেস্ট স্টেশন',
            'location' => 'হাটহাজারী, চট্টগ্রাম',
        ]);

        $response = $this->post(route('stations.crowd-feedback', $station), [
            'crowd_level' => CrowdReport::LEVEL_HIGH,
        ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('crowd_reports', [
            'station_id' => $station->id,
            'crowd_level' => CrowdReport::LEVEL_HIGH,
        ]);
    }

    public function test_public_user_cannot_submit_duplicate_crowd_feedback_within_ten_minutes(): void
    {
        $station = Station::factory()->create();

        CrowdReport::query()->create([
            'station_id' => $station->id,
            'crowd_level' => CrowdReport::LEVEL_LOW,
            'ip_address' => '127.0.0.1',
            'created_at' => Carbon::now()->subMinutes(5),
            'updated_at' => Carbon::now()->subMinutes(5),
        ]);

        $response = $this->from('/')->post(route('stations.crowd-feedback', $station), [
            'crowd_level' => CrowdReport::LEVEL_HIGH,
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors('crowd_level');
        $this->assertDatabaseCount('crowd_reports', 1);
    }

    public function test_admin_actions_are_written_to_audit_log(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.stations.store'), [
            'name' => 'Enterprise Fuel Hub',
            'location' => 'Hathazari Main Road',
            'octane' => '1',
            'diesel' => '1',
        ]);

        $response->assertRedirect(route('admin.stations.index'));

        $this->assertDatabaseHas('admin_audit_logs', [
            'user_id' => $user->id,
            'action' => 'station.created',
        ]);
    }

    public function test_audit_log_page_requires_auth_and_loads_for_admin(): void
    {
        $guestResponse = $this->get('/admin/audit-logs');
        $guestResponse->assertRedirect('/login');

        $user = User::factory()->create();
        AdminAuditLog::query()->create([
            'user_id' => $user->id,
            'action' => 'station.created',
            'description' => 'Created a station.',
        ]);

        $response = $this->actingAs($user)->get(route('admin.audit-logs.index'));

        $response->assertOk();
        $response->assertSee('Audit Logs');
        $response->assertSee('station.created');
    }
}
