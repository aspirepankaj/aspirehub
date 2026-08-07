<?php

namespace Tests\Feature;

use App\Models\User;
use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\Websites\Models\Website;
use App\Modules\CRM\Documents\Models\Document;
use App\Modules\CRM\Documents\Livewire\ManageDocuments;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class DocumentManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Client $client;
    private Website $website;

    protected function setUp(): void
    {
        parent::setUp();

        $role = \App\Modules\Core\Authentication\Models\Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
        ]);

        // Create Admin User
        $this->admin = User::factory()->create();

        \App\Modules\Core\Authentication\Models\Admin::create([
            'user_id' => $this->admin->id,
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        // Create standard User for client
        $clientUser = User::factory()->create();
        $this->client = Client::create([
            'user_id' => $clientUser->id,
            'company_name' => 'Aspire Inc',
            'status' => 'active',
        ]);

        // Create Website
        $this->website = Website::create([
            'client_id' => $this->client->id,
            'site_name' => 'Aspire Website',
            'url' => 'https://aspire.com',
            'added_by' => $this->admin->id,
        ]);
    }

    public function test_admin_can_access_documents_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.documents'));
        $response->assertStatus(200);
    }

    public function test_admin_can_upload_client_document(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('contract.pdf', 500); // 500KB

        Livewire::actingAs($this->admin)
            ->test(ManageDocuments::class)
            ->set('title', 'Client Contract PDF')
            ->set('client_id', $this->client->id)
            ->set('file', $file)
            ->call('saveDocument')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('adspv_documents', [
            'title' => 'Client Contract PDF',
            'client_id' => $this->client->id,
            'website_id' => null,
            'file_name' => 'contract.pdf',
            'file_type' => 'pdf',
        ]);
    }

    public function test_admin_can_upload_website_document(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('credentials.txt', 100);

        Livewire::actingAs($this->admin)
            ->test(ManageDocuments::class)
            ->set('activeTab', 'website')
            ->set('title', 'Website Credentials')
            ->set('client_id', $this->client->id)
            ->set('website_id', $this->website->id)
            ->set('file', $file)
            ->call('saveDocument')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('adspv_documents', [
            'title' => 'Website Credentials',
            'client_id' => $this->client->id,
            'website_id' => $this->website->id,
            'file_name' => 'credentials.txt',
            'file_type' => 'txt',
        ]);
    }

    public function test_admin_can_update_document_details(): void
    {
        Storage::fake('public');

        $doc = Document::create([
            'client_id' => $this->client->id,
            'title' => 'Old Contract',
            'file_path' => 'documents/old.pdf',
            'file_name' => 'old.pdf',
            'file_type' => 'pdf',
            'file_size' => 1024,
            'added_by' => $this->admin->id,
        ]);

        Livewire::actingAs($this->admin)
            ->test(ManageDocuments::class)
            ->call('editDocument', $doc->id)
            ->set('title', 'New Contract')
            ->call('updateDocument')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('adspv_documents', [
            'id' => $doc->id,
            'title' => 'New Contract',
        ]);
    }

    public function test_admin_can_delete_document(): void
    {
        Storage::fake('public');

        $doc = Document::create([
            'client_id' => $this->client->id,
            'title' => 'Temporary Log',
            'file_path' => 'documents/temp.log',
            'file_name' => 'temp.log',
            'file_type' => 'log',
            'file_size' => 200,
            'added_by' => $this->admin->id,
        ]);

        Livewire::actingAs($this->admin)
            ->test(ManageDocuments::class)
            ->call('deleteDocument', $doc->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('adspv_documents', [
            'id' => $doc->id,
        ]);
    }
}
