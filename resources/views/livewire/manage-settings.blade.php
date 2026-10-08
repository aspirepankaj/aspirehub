@section('page_title', 'Settings Center')

<div class="space-y-6">
    <!-- Breadcrumbs -->
    <div class="mb-2">
        <x-admin.breadcrumbs :items="['Settings' => null]" />
    </div>

    <div class="mb-5">
        <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">Settings Center</h1>
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">Manage platform taxonomies, options, and general configurations.</p>
    </div>

    <!-- TABS Navigation (Underline Style) -->
    <div class="border-b border-slate-200 dark:border-slate-800 mb-6">
        <nav class="-mb-px flex space-x-6 sm:space-x-8 overflow-x-auto whitespace-nowrap" aria-label="Tabs">
            <button wire:click="$set('activeTab', 'designations')" class="whitespace-nowrap pb-4 px-1 border-b-2 font-semibold text-sm transition-colors duration-200 flex items-center gap-2 {{ $activeTab === 'designations' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4.674 1.29a3 3 0 00-4.674 0M3 20h18a2 2 0 002-2V6a2 2 0 00-2-2H3a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Designations
            </button>
            <button wire:click="$set('activeTab', 'service-types')" class="whitespace-nowrap pb-4 px-1 border-b-2 font-semibold text-sm transition-colors duration-200 flex items-center gap-2 {{ $activeTab === 'service-types' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2zm10-10V7a3 3 0 00-3-3h-2a3 3 0 00-3 3v3H5a2 2 0 00-2 2v6a2 2 0 002 2h14a2 2 0 002-2v-6a2 2 0 00-2-2h-3z"/>
                </svg>
                Service Types
            </button>
            <button wire:click="$set('activeTab', 'plans')" class="whitespace-nowrap pb-4 px-1 border-b-2 font-semibold text-sm transition-colors duration-200 flex items-center gap-2 {{ $activeTab === 'plans' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Plans
            </button>
        </nav>
    </div>

    <!-- TABS Content -->
    <div class="mt-4 settings-tab-content">
        @if ($activeTab === 'designations')
            <div class="animate-fadeIn">
                @livewire(\App\Modules\CRM\Staff\Livewire\ManageDesignations::class, key('designations-'.time()))
            </div>
        @elseif ($activeTab === 'service-types')
            <div class="animate-fadeIn">
                @livewire(\App\Modules\CRM\Websites\Livewire\ManageServiceTypes::class, key('service-types-'.time()))
            </div>
        @elseif ($activeTab === 'plans')
            <div class="animate-fadeIn">
                @livewire(\App\Modules\CRM\Clients\Livewire\ManagePlans::class, key('plans-'.time()))
            </div>
        @endif
    </div>

    <style>
        /* Hide redundant breadcrumbs inside the nested components */
        .settings-tab-content > div > div > div.flex.items-center.justify-between.gap-4.mb-5:first-of-type {
            display: none !important;
        }

        /* Hide the description paragraph before the Add button */
        .settings-tab-content p.text-xs.text-slate-400.dark\:text-slate-500.font-medium {
            display: none !important;
        }

        /* Align the "Add" button to the right since paragraph is hidden */
        .settings-tab-content > div > div > div.flex.items-center.justify-between.gap-4.mb-5 {
            justify-content: flex-end !important;
        }
    </style>
</div>
