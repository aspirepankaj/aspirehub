@section('page_title', 'Websites')

@php
    $typeColors = [
        'maintenance'        => 'emerald',
        'design'             => 'pink',
        'development'        => 'indigo',
        'speed_optimisation' => 'amber',
        'other'              => 'slate',
    ];
    $typeLabels = [
        'maintenance'        => 'Maintenance',
        'design'             => 'Design',
        'development'        => 'Development',
        'speed_optimisation' => 'Speed Optimisation',
        'other'              => 'Other',
    ];
    $statusColors = [
        'active'    => 'emerald',
        'inactive'  => 'slate',
        'suspended' => 'red',
    ];
@endphp

<div>
    {{-- Breadcrumbs --}}
    <x-admin.breadcrumbs :items="['Websites' => null]" />

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between gap-4 mb-5">
        <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">
            Track and manage websites for your assigned clients
        </p>

        <div class="flex items-center gap-2.5">
            {{-- Add Website button --}}
            <button type="button" wire:click="openAddModal"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white text-xs font-bold transition-all duration-150 shadow-sm whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Website
            </button>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-4 mb-5 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            {{-- Search --}}
            <div class="relative flex items-center">
                <svg class="absolute left-3 w-4 h-4 text-slate-400 dark:text-slate-500 pointer-events-none shrink-0 z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input wire:model.live.debounce.300ms="search"
                       type="text"
                       placeholder="Search by site name or URL..."
                       class="block w-full pl-9 pr-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150" />
            </div>

            {{-- Status Filter --}}
            <div class="relative flex items-center">
                <select wire:model.live="statusFilter"
                        class="block w-full px-3 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-855 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>

            {{-- Service Type Filter --}}
            <div class="relative flex items-center">
                <select wire:model.live="serviceTypeFilter"
                        class="block w-full px-3 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-855 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150">
                    <option value="">All Service Types</option>
                    @foreach($serviceTypes as $typeOpt)
                        <option value="{{ $typeOpt->id }}">{{ $typeOpt->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Plan Filter --}}
            <div class="relative flex items-center">
                <select wire:model.live="planFilter"
                        class="block w-full px-3 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/50 text-slate-855 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40 focus:border-indigo-400 text-sm transition duration-150">
                    <option value="">All Plans</option>
                    @foreach($plans as $planOpt)
                        <option value="{{ $planOpt->id }}">{{ $planOpt->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($hasActiveFilters)
            <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800/60 flex items-center justify-between">
                <span class="text-xs text-slate-400 dark:text-slate-500 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Filters active — showing filtered results
                </span>
                <button type="button" wire:click="clearFilters"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200/60 dark:border-red-800/30 hover:bg-red-100 dark:hover:bg-red-500/20 text-xs font-bold transition-all duration-150 active:scale-95">
                    Clear Filters
                </button>
            </div>
        @endif
    </div>

    {{-- Flash Message --}}
    @if (session('success'))
        <x-admin.alert type="success" class="mb-6" :message="session('success')" />
    @endif

    {{-- Websites Table Card --}}
    <x-admin.card>
        @if($websites->isEmpty())
            <div class="text-center py-16">
                <svg class="w-14 h-14 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-300 mb-1">No Websites Found</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">Try refining your search or add a new website above.</p>
            </div>
        @else
            <div class="overflow-x-auto rounded-2xl border border-slate-200/50 dark:border-slate-800/50">
                <table class="w-full text-left border-collapse bg-white/40 dark:bg-slate-900/10 backdrop-blur-md">
                    <thead>
                        <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-950/20 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                            <th class="px-4 py-4">Website</th>
                            <th class="px-4 py-4">Client</th>
                            <th class="px-4 py-4">Service Type</th>
                            <th class="px-4 py-4">Status</th>
                            <th class="px-4 py-4">Hosting</th>
                            <th class="px-4 py-4">Added</th>
                            <th class="px-4 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50 text-sm">
                        @foreach($websites as $website)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                                {{-- Website Name + URL --}}
                                <td class="px-4 py-4">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $website->site_name }}</div>
                                    <a href="{{ $website->url }}" target="_blank"
                                       class="text-xs text-indigo-500 hover:text-indigo-700 dark:text-indigo-400 font-medium truncate max-w-[200px] block">
                                        {{ $website->url }}
                                    </a>
                                </td>

                                {{-- Client --}}
                                <td class="px-4 py-4">
                                    <div class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $website->client->user->name ?? '—' }}
                                    </div>
                                    <div class="text-xs text-slate-400 dark:text-slate-500">
                                        {{ $website->client->company_name ?: ($website->client->user->email ?? '') }}
                                    </div>
                                </td>

                                {{-- Service Type Badge --}}
                                <td class="px-4 py-4">
                                    @if($website->serviceTypes->isNotEmpty())
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($website->serviceTypes as $sT)
                                                <span class="px-2.5 py-1 text-xs font-bold rounded-lg
                                                    bg-{{ $sT->color }}-500/10 text-{{ $sT->color }}-600 dark:text-{{ $sT->color }}-400 uppercase tracking-wider text-[10px]">
                                                    {{ $sT->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        —
                                    @endif
                                </td>

                                {{-- Status Badge --}}
                                <td class="px-4 py-4">
                                    @php
                                        $statColor = $statusColors[$website->status] ?? 'slate';
                                    @endphp
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg
                                        bg-{{ $statColor }}-500/10 text-{{ $statColor }}-600 dark:text-{{ $statColor }}-400">
                                        {{ ucfirst($website->status) }}
                                    </span>
                                </td>

                                {{-- Hosting --}}
                                <td class="px-4 py-4 text-sm text-slate-500 dark:text-slate-400 font-medium">
                                    {{ $website->hosting_provider ?: '—' }}
                                </td>

                                {{-- Added At --}}
                                <td class="px-4 py-4 text-xs text-slate-400 dark:text-slate-500 font-medium">
                                    {{ $website->created_at->diffForHumans() }}
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 py-4 text-right">
                                    <button type="button" wire:click="editWebsite({{ $website->id }})"
                                            class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150 active:scale-90">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $websites->links() }}
            </div>
        @endif
    </x-admin.card>

    {{-- ADD WEBSITE MODAL --}}
    <x-admin.modal name="add-website-modal" title="Add New Website" maxWidth="max-w-3xl">
        <form wire:submit.prevent="saveWebsite" class="space-y-4 mt-2">
            <!-- Website Image -->
            <div>
                <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Website Image') }}</label>
                <div class="mt-1.5 flex items-center gap-3">
                    @if ($image)
                        <img src="{{ Str::startsWith($image, 'http') ? $image : asset('storage/' . $image) }}" class="w-16 h-16 rounded-xl object-cover border border-slate-200 dark:border-slate-800" />
                    @else
                        <div class="w-16 h-16 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700/50 flex items-center justify-center text-slate-400">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    
                    <button type="button" @click="$dispatch('open-media-picker', { field: 'image' })" class="px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:text-indigo-400 font-semibold text-xs rounded-lg transition-colors border border-indigo-200 dark:border-indigo-800">
                        Choose from Media Library
                    </button>
                    
                    @if ($image)
                        <button type="button" wire:click="removeImage" class="px-3 py-1.5 text-xs font-semibold text-red-600 bg-white border border-red-200 rounded-xl hover:bg-red-50 transition shadow-sm">
                            Remove
                        </button>
                    @endif
                </div>
                <x-input-error :messages="$errors->get('image')" class="mt-1" />
            </div>

            {{-- Client Dropdown --}}
            <div>
                <label class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Client *</label>
                <select wire:model="client_id"
                        class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                    <option value="">— Select Client —</option>
                    @foreach($clients as $c)
                        <option value="{{ $c->id }}">{{ $c->company_name ?: $c->user->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('client_id')" class="mt-1" />
            </div>

            {{-- Site Name --}}
            <div>
                <label for="add_site_name" class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Site Name *</label>
                <input wire:model="site_name" id="add_site_name" type="text" placeholder="e.g. Acme Corp Website"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('site_name')" class="mt-1" />
            </div>

            {{-- URL --}}
            <div>
                <label for="add_url" class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Website URL *</label>
                <input wire:model="url" id="add_url" type="url" placeholder="https://example.com"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('url')" class="mt-1" />
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Service Types *</label>
                    <div class="grid grid-cols-1 gap-2 mt-1.5 p-2 rounded-xl bg-slate-50 dark:bg-slate-95c border border-slate-200 dark:border-slate-800 max-h-28 overflow-y-auto">
                        @foreach($serviceTypes as $typeOpt)
                            <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition duration-150 cursor-pointer">
                                <input type="checkbox" wire:model="service_type_ids" value="{{ $typeOpt->id }}"
                                       class="rounded border-slate-200 dark:border-slate-800 text-indigo-600 focus:ring-indigo-500/50">
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $typeOpt->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('service_type_ids')" class="mt-1" />
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Plans (Optional)</label>
                    <div class="grid grid-cols-1 gap-2 mt-1.5 p-2 rounded-xl bg-slate-50 dark:bg-slate-95c border border-slate-200 dark:border-slate-800 max-h-28 overflow-y-auto">
                        @foreach($plans as $planOpt)
                            <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition duration-150 cursor-pointer">
                                <input type="checkbox" wire:model="plan_ids" value="{{ $planOpt->id }}"
                                       class="rounded border-slate-200 dark:border-slate-800 text-indigo-600 focus:ring-indigo-500/50">
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $planOpt->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('plan_ids')" class="mt-1" />
                </div>
                <div>
                    <label for="add_status" class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Status *</label>
                    <select wire:model="status" id="add_status"
                            class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>
            </div>      </div>

            {{-- Credentials --}}
            <div class="pt-1">
                <p class="text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                    CMS / Admin Login Credentials
                </p>
                <div class="space-y-3">
                    <div>
                        <label for="add_admin_url" class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Admin Login URL</label>
                        <input wire:model="admin_url" id="add_admin_url" type="url" placeholder="https://example.com/wp-admin"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm" />
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="add_admin_username" class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Admin Username</label>
                            <input wire:model="admin_username" id="add_admin_username" type="text" placeholder="admin"
                                   class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm" />
                        </div>
                        <div>
                            <label for="add_admin_password" class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Admin Password</label>
                            <input wire:model="admin_password" id="add_admin_password" type="text" placeholder="••••••••"
                                   class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hosting --}}
            <div class="pt-1">
                <p class="text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                    Hosting Details
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="add_hosting_provider" class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Hosting Provider</label>
                        <input wire:model="hosting_provider" id="add_hosting_provider" type="text" placeholder="e.g. SiteGround"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm" />
                    </div>
                    <div>
                        <label for="add_server_ip" class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Server IP</label>
                        <input wire:model="server_ip" id="add_server_ip" type="text" placeholder="e.g. 192.168.1.1"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm" />
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label for="add_notes" class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Notes</label>
                <textarea wire:model="notes" id="add_notes" rows="3" placeholder="Any notes..."
                          class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" @click="show = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-350 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-650 hover:bg-indigo-750 text-white text-xs font-semibold shadow active:scale-95 transition-all">
                    Save Website
                </button>
            </div>
        </form>
    </x-admin.modal>

    {{-- EDIT WEBSITE MODAL --}}
    <x-admin.modal name="edit-website-modal" title="Edit Website" maxWidth="max-w-3xl">
        <form wire:submit.prevent="updateWebsite" class="space-y-4 mt-2">
            <!-- Website Image -->
            <div>
                <label class="block text-[10px] font-extrabold text-slate-600 dark:text-slate-500 uppercase tracking-widest">{{ __('Website Image') }}</label>
                <div class="mt-1.5 flex items-center gap-3">
                    @if ($image)
                        <img src="{{ Str::startsWith($image, 'http') ? $image : asset('storage/' . $image) }}" class="w-16 h-16 rounded-xl object-cover border border-slate-200 dark:border-slate-800" />
                    @elseif($existing_image)
                        <img src="{{ asset('storage/' . $existing_image) }}" class="w-16 h-16 rounded-xl object-cover border border-slate-200 dark:border-slate-800" />
                    @else
                        <div class="w-16 h-16 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-250 dark:border-slate-700/50 flex items-center justify-center text-slate-400">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    
                    <button type="button" @click="$dispatch('open-media-picker', { field: 'image' })" class="px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:text-indigo-400 font-semibold text-xs rounded-lg transition-colors border border-indigo-200 dark:border-indigo-800">
                        Choose from Media Library
                    </button>
                    
                    @if ($image || $existing_image)
                        <button type="button" wire:click="removeImage" class="px-3 py-1.5 text-xs font-semibold text-red-600 bg-white border border-red-200 rounded-xl hover:bg-red-50 transition shadow-sm">
                            Remove
                        </button>
                    @endif
                </div>
                <x-input-error :messages="$errors->get('image')" class="mt-1" />
            </div>

            {{-- Client Dropdown --}}
            <div>
                <label class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Client *</label>
                <select wire:model="client_id"
                        class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                    <option value="">— Select Client —</option>
                    @foreach($clients as $c)
                        <option value="{{ $c->id }}">{{ $c->company_name ?: $c->user->name }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('client_id')" class="mt-1" />
            </div>

            {{-- Site Name --}}
            <div>
                <label for="edit_site_name" class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Site Name *</label>
                <input wire:model="site_name" id="edit_site_name" type="text" placeholder="e.g. Acme Corp Website"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('site_name')" class="mt-1" />
            </div>

            {{-- URL --}}
            <div>
                <label for="edit_url" class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Website URL *</label>
                <input wire:model="url" id="edit_url" type="url" placeholder="https://example.com"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('url')" class="mt-1" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Service Types *</label>
                    <div class="grid grid-cols-1 gap-2 mt-1.5 p-2 rounded-xl bg-slate-50 dark:bg-slate-95c border border-slate-200 dark:border-slate-800 max-h-28 overflow-y-auto">
                        @foreach($serviceTypes as $typeOpt)
                            <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition duration-150 cursor-pointer">
                                <input type="checkbox" wire:model="service_type_ids" value="{{ $typeOpt->id }}"
                                       class="rounded border-slate-200 dark:border-slate-800 text-indigo-600 focus:ring-indigo-500/50">
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $typeOpt->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('service_type_ids')" class="mt-1" />
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Plans (Optional)</label>
                    <div class="grid grid-cols-1 gap-2 mt-1.5 p-2 rounded-xl bg-slate-50 dark:bg-slate-95c border border-slate-200 dark:border-slate-800 max-h-28 overflow-y-auto">
                        @foreach($plans as $planOpt)
                            <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition duration-150 cursor-pointer">
                                <input type="checkbox" wire:model="plan_ids" value="{{ $planOpt->id }}"
                                       class="rounded border-slate-200 dark:border-slate-800 text-indigo-600 focus:ring-indigo-500/50">
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-200 truncate">{{ $planOpt->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('plan_ids')" class="mt-1" />
                </div>
                <div>
                    <label for="edit_status" class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Status *</label>
                    <select wire:model="status" id="edit_status"
                            class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>
            </div>

            {{-- Credentials --}}
            <div class="pt-1">
                <p class="text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                    CMS / Admin Login Credentials
                </p>
                <div class="space-y-3">
                    <div>
                        <label for="edit_admin_url" class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Admin Login URL</label>
                        <input wire:model="admin_url" id="edit_admin_url" type="url" placeholder="https://example.com/wp-admin"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm" />
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="edit_admin_username" class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Admin Username</label>
                            <input wire:model="admin_username" id="edit_admin_username" type="text" placeholder="admin"
                                   class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm" />
                        </div>
                        <div>
                            <label for="edit_admin_password" class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Admin Password</label>
                            <input wire:model="admin_password" id="edit_admin_password" type="text" placeholder="••••••••"
                                   class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hosting --}}
            <div class="pt-1">
                <p class="text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                    Hosting Details
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="edit_hosting_provider" class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Hosting Provider</label>
                        <input wire:model="hosting_provider" id="edit_hosting_provider" type="text" placeholder="e.g. SiteGround"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm" />
                    </div>
                    <div>
                        <label for="edit_server_ip" class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Server IP</label>
                        <input wire:model="server_ip" id="edit_server_ip" type="text" placeholder="e.g. 192.168.1.1"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm" />
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label for="edit_notes" class="block text-[10px] font-extrabold text-slate-650 dark:text-slate-500 uppercase tracking-widest">Notes</label>
                <textarea wire:model="notes" id="edit_notes" rows="3" placeholder="Any notes..."
                          class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm"></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" @click="show = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-355 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-indigo-650 hover:bg-indigo-755 text-white text-xs font-semibold shadow active:scale-95 transition-all">
                    Update Details
                </button>
            </div>
        </form>
    </x-admin.modal>
</div>
