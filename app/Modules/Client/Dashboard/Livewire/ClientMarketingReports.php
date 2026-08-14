<?php

namespace App\Modules\Client\Dashboard\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.client-portal')]
class ClientMarketingReports extends Component
{
    public function render()
    {
        return view('modules.client.dashboard.client-marketing-reports')->layoutData(['title' => 'Marketing Reports']);
    }
}
