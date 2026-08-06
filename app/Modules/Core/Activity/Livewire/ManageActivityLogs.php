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
    public string $dateFrom = '';
    public string $dateTo = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingActionFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->actionFilter = '';
        $this->dateFrom = '';
        $this->dateTo = '';
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
            ->when($this->dateFrom, function ($q) {
                $q->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($q) {
                $q->whereDate('created_at', '<=', $this->dateTo);
            })
            ->latest()
            ->paginate(15);

        $hasActiveFilters = $this->search || $this->actionFilter || $this->dateFrom || $this->dateTo;

        return view('modules.core.activity.manage-activity-logs', [
            'logs' => $logs,
            'hasActiveFilters' => $hasActiveFilters,
        ])->layoutData(['title' => 'System Activity Logs - Aspire Hub']);
    }
}
