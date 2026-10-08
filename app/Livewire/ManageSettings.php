<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class ManageSettings extends Component
{
    public string $activeTab = 'designations';

    protected $queryString = ['activeTab' => ['except' => 'designations', 'as' => 'tab']];

    public function render()
    {
        return view('livewire.manage-settings')->layoutData(['title' => 'Settings Center - Aspire Digital Solutions']);
    }
}
