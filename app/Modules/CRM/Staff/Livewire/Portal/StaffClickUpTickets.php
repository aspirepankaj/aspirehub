<?php

namespace App\Modules\CRM\Staff\Livewire\Portal;

use App\Modules\CRM\Clients\Models\Client;
use App\Modules\CRM\ClickUp\Models\ClickUpFolder;
use App\Services\ClickUpService;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.staff')]
class StaffClickUpTickets extends Component
{
    public array $tasks = [];
    public bool $tasksLoaded = false;
    
    // Filters & View State
    public string $viewFilter = 'assigned_to_me'; // 'assigned_to_me' or 'all_client_tickets'
    public string $search = '';
    public string $statusFilter = '';
    public string $clientFilter = '';

    public function setViewFilter(string $filter): void
    {
        $this->viewFilter = $filter;
    }

    public function mount(ClickUpService $clickUpService): void
    {
        $this->loadTasks($clickUpService);
    }

    public function loadTasks(ClickUpService $clickUpService): void
    {
        $staff = auth()->user()->staff;
        if (!$staff) {
            $this->tasks = [];
            $this->tasksLoaded = true;
            return;
        }

        $assignedClientIds = Client::whereHas('assignedStaff', function ($q) use ($staff) {
            $q->where('staff_id', $staff->id);
        })->pluck('id')->toArray();

        if (empty($assignedClientIds)) {
            $this->tasks = [];
            $this->tasksLoaded = true;
            return;
        }

        try {
            $allTasks = [];
            foreach ($assignedClientIds as $clientId) {
                $client = Client::with('user')->find($clientId);
                $cName = $client->company_name ?: ($client->user->name ?? "Client #{$clientId}");
                $cTasks = $clickUpService->fetchClientTasks($clientId);

                foreach ($cTasks as $t) {
                    $t['client_id'] = $clientId;
                    $t['client_name'] = $cName;
                    $allTasks[] = $t;
                }
            }

            usort($allTasks, function ($a, $b) {
                return ($b['created_timestamp'] ?? 0) <=> ($a['created_timestamp'] ?? 0);
            });

            $this->tasks = $allTasks;
            $this->tasksLoaded = true;
        } catch (\Exception $e) {
            $this->tasks = [];
            $this->tasksLoaded = true;
        }
    }

    public function syncTasks(ClickUpService $clickUpService): void
    {
        $this->tasksLoaded = false;
        $this->loadTasks($clickUpService);
        session()->flash('success', 'ClickUp tickets synced successfully!');
    }

    public function render()
    {
        $staffUser = auth()->user();
        $staffEmail = strtolower(trim($staffUser->email ?? ''));
        $staffName = strtolower(trim($staffUser->name ?? ''));

        $filteredTasks = collect($this->tasks);

        // 1. View Filter: Assigned to Me vs All Assigned Client Tickets
        if ($this->viewFilter === 'assigned_to_me') {
            $filteredTasks = $filteredTasks->filter(function ($task) use ($staffEmail, $staffName) {
                if (empty($task['assignees'])) return false;
                foreach ($task['assignees'] as $assignee) {
                    $aEmail = strtolower(trim($assignee['email'] ?? ''));
                    $aName = strtolower(trim($assignee['username'] ?? ''));
                    if ($staffEmail && $aEmail === $staffEmail) {
                        return true;
                    }
                    if ($staffName && (str_contains($aName, $staffName) || str_contains($staffName, $aName))) {
                        return true;
                    }
                }
                return false;
            });
        }

        // 2. Search Filter
        if (!empty($this->search)) {
            $s = strtolower(trim($this->search));
            $filteredTasks = $filteredTasks->filter(function ($t) use ($s) {
                return str_contains(strtolower($t['name']), $s) ||
                       str_contains(strtolower($t['description'] ?? ''), $s) ||
                       str_contains(strtolower($t['client_name'] ?? ''), $s) ||
                       str_contains(strtolower($t['folder_name'] ?? ''), $s);
            });
        }

        // 3. Status Filter
        if (!empty($this->statusFilter)) {
            $sf = strtolower(trim($this->statusFilter));
            $filteredTasks = $filteredTasks->filter(fn($t) => strtolower(trim($t['status'])) === $sf);
        }

        // 4. Client Filter
        if (!empty($this->clientFilter)) {
            $cf = (int) $this->clientFilter;
            $filteredTasks = $filteredTasks->filter(fn($t) => (int) $t['client_id'] === $cf);
        }

        // Unique Statuses & Clients for dropdown filters
        $statuses = collect($this->tasks)->pluck('status')->filter()->unique()->sort()->values();
        $clients = collect($this->tasks)->map(fn($t) => [
            'id' => $t['client_id'],
            'name' => $t['client_name']
        ])->unique('id')->sortBy('name')->values();

        $assignedToMeCount = collect($this->tasks)->filter(function ($task) use ($staffEmail, $staffName) {
            if (empty($task['assignees'])) return false;
            foreach ($task['assignees'] as $assignee) {
                $aEmail = strtolower(trim($assignee['email'] ?? ''));
                $aName = strtolower(trim($assignee['username'] ?? ''));
                if ($staffEmail && $aEmail === $staffEmail) return true;
                if ($staffName && (str_contains($aName, $staffName) || str_contains($staffName, $aName))) return true;
            }
            return false;
        })->count();

        return view('modules.crm.staff.portal.clickup-tickets', [
            'tickets' => $filteredTasks->values(),
            'statuses' => $statuses,
            'clients' => $clients,
            'assignedToMeCount' => $assignedToMeCount,
            'totalTicketsCount' => count($this->tasks),
        ])->layoutData(['title' => 'My ClickUp Tickets - Staff Portal']);
    }
}
