<?php

namespace App\Modules\Core\Activity\Livewire;

use App\Modules\Core\Activity\Models\EmailLog;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ManageEmailLogs extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $dateFrom = '';
    public string $dateTo = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
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
        $this->statusFilter = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }

    public function render()
    {
        $logs = EmailLog::with(['sender', 'report.website'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('recipient_email', 'like', '%' . $this->search . '%')
                        ->orWhere('subject', 'like', '%' . $this->search . '%')
                        ->orWhereHas('sender', function ($uq) {
                            $uq->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->dateFrom, function ($q) {
                $q->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($q) {
                $q->whereDate('created_at', '<=', $this->dateTo);
            })
            ->latest()
            ->paginate(15);

        $hasActiveFilters = $this->search || $this->statusFilter || $this->dateFrom || $this->dateTo;

        return view('modules.core.activity.manage-email-logs', [
            'logs' => $logs,
            'hasActiveFilters' => $hasActiveFilters,
        ])->layoutData(['title' => 'Email Dispatch Logs - Aspire Hub']);
    }
}
