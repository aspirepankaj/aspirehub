<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\Core\Activity\Models\ActivityLog;
use App\Modules\CRM\Clients\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogsTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->create(['name' => 'Admin User']);
    }

    public function test_creating_client_records_activity_log()
    {
        $this->actingAs($this->adminUser);

        $clientUser = User::factory()->create(['name' => 'John Doe']);
        $client = Client::create([
            'user_id' => $clientUser->id,
            'company_name' => 'Acme Corp',
            'status' => 'active',
            'added_by' => $this->adminUser->id,
        ]);

        $this->assertDatabaseHas('adspv_activity_logs', [
            'user_id' => $this->adminUser->id,
            'action' => 'created',
            'loggable_type' => Client::class,
            'loggable_id' => $client->id,
            'description' => "Admin User added new client: 'Acme Corp'",
        ]);
    }
}
