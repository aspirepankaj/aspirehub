<?php

namespace App\Modules\Core\Activity\Livewire;

use App\Modules\Core\Activity\Models\ActivityLog;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ManageActivityLogs extends Component
{
    use WithPagination;

    public string $search = '';
    public string $actionFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingActionFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $logs = ActivityLog::with('user')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('description', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($uq) {
                            $uq->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->actionFilter, function ($q) {
                $q->where('action', $this->actionFilter);
            })
            ->latest()
            ->paginate(15);

        return view('modules.core.activity.manage-activity-logs', [
            'logs' => $logs
        ])->layoutData(['title' => 'System Activity Logs - Aspire Hub']);
    }
}
