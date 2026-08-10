<?php

namespace App\Modules\Core\Authentication\Livewire;

use App\Models\User;
use App\Modules\Core\Authentication\Models\Admin;
use App\Modules\Core\Authentication\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ManageAdmins extends Component
{
    use WithPagination;
    use WithFileUploads;

    // Form fields
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public ?int $role_id = null;
    public string $phone = '';
    public $profile_image;
    public ?string $existing_profile_image = null;
    public int $is_active = 1;

    // Search and filter
    public string $search = '';
    public string $statusFilter = '';
    public ?int $roleFilter = null;

    // Bulk actions
    public array $selectedAdmins = [];
    public bool $selectAll = false;

    // Edit tracking
    public ?int $editingAdminId = null;
    public ?int $editingUserId = null;

    public function updatingSearch(): void
    {
        $this->selectedAdmins = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->roleFilter = null;
        $this->selectedAdmins = [];
        $this->selectAll = false;
        $this->resetPage();
    }

    public function toggleSelectAll(array $pageIds): void
    {
        if ($this->selectAll) {
            $this->selectedAdmins = $pageIds;
        } else {
            $this->selectedAdmins = [];
        }
    }

    public function resetForm(): void
    {
        $this->reset([
            'name',
            'email',
            'password',
            'role_id',
            'phone',
            'profile_image',
            'existing_profile_image',
            'is_active',
            'editingAdminId',
            'editingUserId'
        ]);
        $this->resetValidation();
    }

    public function openAddModal(): void
    {
        $this->resetForm();
        $this->dispatch('open-modal', name: 'add-admin-modal');
    }

    public function saveAdmin(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:30',
            'profile_image' => 'nullable|image|max:1024',
        ]);

        $profileImagePath = null;
        if ($this->profile_image) {
            $profileImagePath = $this->profile_image->store('profile_images', 'public');
        }

        $adminRole = Role::where('slug', 'admin')->first() ?? Role::where('name', 'Administrator')->first();
        $roleId = $adminRole ? $adminRole->id : null;

        DB::transaction(function () use ($profileImagePath, $roleId) {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            Admin::create([
                'user_id' => $user->id,
                'role_id' => $roleId,
                'is_active' => (bool) $this->is_active,
                'profile_image' => $profileImagePath,
                'phone' => $this->phone,
            ]);
        });

        $this->dispatch('close-modal', name: 'add-admin-modal');
        $this->resetForm();
        session()->flash('success', 'Admin created successfully!');
    }

    public function editAdmin(int $id): void
    {
        $admin = Admin::with('user')->findOrFail($id);

        $this->editingAdminId = $admin->id;
        $this->editingUserId = $admin->user_id;

        $this->name = $admin->user->name;
        $this->email = $admin->user->email;
        $this->password = '';
        $this->phone = $admin->phone ?? '';
        $this->existing_profile_image = $admin->profile_image;
        $this->is_active = $admin->is_active ? 1 : 0;

        $this->resetValidation();
        $this->dispatch('open-modal', name: 'edit-admin-modal');
    }

    public function updateAdmin(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->editingUserId,
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:30',
            'profile_image' => 'nullable|image|max:1024',
        ]);

        $profileImagePath = $this->existing_profile_image;
        if ($this->profile_image) {
            $profileImagePath = $this->profile_image->store('profile_images', 'public');
        }

        $adminRole = Role::where('slug', 'admin')->first() ?? Role::where('name', 'Administrator')->first();
        $roleId = $adminRole ? $adminRole->id : null;

        DB::transaction(function () use ($profileImagePath, $roleId) {
            $user = User::findOrFail($this->editingUserId);
            $userUpdateData = [
                'name' => $this->name,
                'email' => $this->email,
            ];
            if (!empty($this->password)) {
                $userUpdateData['password'] = Hash::make($this->password);
            }
            $user->update($userUpdateData);

            $admin = Admin::findOrFail($this->editingAdminId);
            $admin->update([
                'role_id' => $roleId,
                'is_active' => (bool) $this->is_active,
                'profile_image' => $profileImagePath,
                'phone' => $this->phone,
            ]);
        });

        $this->dispatch('close-modal', name: 'edit-admin-modal');
        $this->resetForm();
        session()->flash('success', 'Admin updated successfully!');
    }

    public function deleteAdmin(int $id): void
    {
        if ($id === auth()->user()->admin?->id) {
            session()->flash('error', 'You cannot delete your own admin profile.');
            return;
        }

        $admin = Admin::findOrFail($id);
        
        DB::transaction(function () use ($admin) {
            $user = User::find($admin->user_id);
            $admin->delete();
            if ($user) {
                $user->delete();
            }
        });

        session()->flash('success', 'Admin deleted successfully!');
    }

    public function bulkDelete(): void
    {
        if (empty($this->selectedAdmins)) return;

        $authAdminId = auth()->user()->admin?->id;
        $filteredIds = array_filter($this->selectedAdmins, fn($id) => $id !== $authAdminId);

        if (empty($filteredIds)) {
            session()->flash('error', 'You cannot delete your own admin profile.');
            return;
        }

        DB::transaction(function () use ($filteredIds) {
            $admins = Admin::whereIn('id', $filteredIds)->get();
            $userIds = $admins->pluck('user_id')->toArray();
            Admin::whereIn('id', $filteredIds)->delete();
            User::whereIn('id', $userIds)->delete();
        });

        $count = count($filteredIds);
        $this->selectedAdmins = [];
        $this->selectAll = false;
        session()->flash('success', "{$count} admin(s) deleted successfully.");
    }

    public function render()
    {
        $searchTerm = trim($this->search);

        $admins = Admin::with(['user', 'role'])
            ->whereHas('user', function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%');
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter === 'active');
            })
            ->latest()
            ->paginate(10);

        $hasActiveFilters = $this->search !== '' || $this->statusFilter !== '';
        $pageIds = $admins->pluck('id')->toArray();

        return view('modules.core.authentication.manage-admins', [
            'admins'           => $admins,
            'hasActiveFilters' => $hasActiveFilters,
            'pageIds'          => $pageIds,
        ])->layoutData(['title' => 'Admin Management - Aspire Hub']);
    }
}
