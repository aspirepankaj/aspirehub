<?php

namespace App\Modules\CRM\Staff\Livewire\Portal;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.staff')]
class StaffProfile extends Component
{
    public function render()
    {
        return view('modules.crm.staff.portal.profile')->layoutData(['title' => 'My Profile - Staff Portal']);
    }
}
