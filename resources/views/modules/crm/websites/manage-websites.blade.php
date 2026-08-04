@section('page_title', 'Websites Management')

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

    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
        {{-- Search --}}
        <div class="relative w-full sm:max-w-md">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 dark:text-slate-500">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input wire:model.live.debounce.300ms="search"
                   type="text"
                   autocomplete="off"
                   placeholder="Search by site name, URL, or client..."
                   class="block w-full pl-11 pr-4.5 py-2.5 rounded-xl bg-white/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
        </div>

        {{-- Add Website Button --}}
        <x-admin.button wire:click="openAddModal" size="md" variant="primary" class="w-full sm:w-auto space-x-2">
            <svg class="w-4 h-4 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span>Add Website</span>
        </x-admin.button>
    </div>

    {{-- Flash Message --}}
    @if (session('success'))
        <x-admin.alert type="success" class="mb-6" :message="session('success')" />
    @endif

    {{-- Websites Table --}}
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
            <x-admin.table :headers="['Website', 'Client', 'Service Type', 'Status', 'Hosting', 'Added', 'Actions']">
                @foreach($websites as $website)
                    @php
                        $typeColor  = $typeColors[$website->site_type]  ?? 'slate';
                        $typeLabel  = $typeLabels[$website->site_type]  ?? 'Other';
                        $statColor  = $statusColors[$website->status]   ?? 'slate';
                    @endphp
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                        {{-- Website Name + URL --}}
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900 dark:text-white">{{ $website->site_name }}</div>
                            <a href="{{ $website->url }}" target="_blank"
                               class="text-xs text-indigo-500 hover:text-indigo-700 dark:text-indigo-400 font-medium truncate max-w-[200px] block">
                                {{ $website->url }}
                            </a>
                        </td>

                        {{-- Client --}}
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                {{ $website->client->company_name ?: ($website->client->user->name ?? '—') }}
                            </div>
                            <div class="text-xs text-slate-400 dark:text-slate-500">
                                {{ $website->client->user->email ?? '' }}
                            </div>
                        </td>

                        {{-- Service Type Badge --}}
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg
                                bg-{{ $typeColor }}-500/10 text-{{ $typeColor }}-600 dark:text-{{ $typeColor }}-400">
                                {{ $typeLabel }}
                            </span>
                        </td>

                        {{-- Status Badge --}}
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg
                                bg-{{ $statColor }}-500/10 text-{{ $statColor }}-600 dark:text-{{ $statColor }}-400">
                                {{ ucfirst($website->status) }}
                            </span>
                        </td>

                        {{-- Hosting --}}
                        <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400 font-medium">
                            {{ $website->hosting_provider ?: '—' }}
                        </td>

                        {{-- Added At --}}
                        <td class="px-6 py-4 text-xs text-slate-400 dark:text-slate-500 font-medium">
                            {{ $website->created_at->diffForHumans() }}
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 text-right">
                            <button type="button" wire:click="editWebsite({{ $website->id }})"
                                    class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-all duration-150 active:scale-90">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>

            <div class="mt-6">
                {{ $websites->links() }}
            </div>
        @endif
    </x-admin.card>

    {{-- ═══════════════════════════════════════════════════════════
         ADD WEBSITE MODAL
    ═══════════════════════════════════════════════════════════ --}}
    <x-admin.modal name="add-website-modal" title="Add New Website">
        <div class="space-y-4 mt-2">

            {{-- Client Searchable Dropdown (Add Modal) --}}
            <div
                x-data="{
                    open: false,
                    search: '',
                    selectedId: @entangle('client_id'),
                    selectedLabel: '',
                    clients: {{ Js::from($clients->map(fn($c) => ['id' => $c->id, 'label' => $c->company_name ?: $c->user->name, 'sub' => $c->user->email ?? ''])) }},
                    get filtered() {
                        if (!this.search) return this.clients;
                        const q = this.search.toLowerCase();
                        return this.clients.filter(c =>
                            c.label.toLowerCase().includes(q) || c.sub.toLowerCase().includes(q)
                        );
                    },
                    select(c) {
                        this.selectedId = c.id;
                        this.selectedLabel = c.label;
                        this.search = '';
                        this.open = false;
                        $wire.set('client_id', c.id);
                    },
                    syncLabel() {
                        if (!this.selectedId) { this.selectedLabel = ''; return; }
                        const found = this.clients.find(c => c.id == this.selectedId);
                        this.selectedLabel = found ? found.label : '';
                    },
                    init() {
                        this.syncLabel();
                        this.$watch('selectedId', () => this.syncLabel());
                        this.$watch('open', v => { if (v) this.$nextTick(() => this.$refs.addSearch.focus()); });
                    }
                }"
                x-on:close-modal.window="open = false"
                class="relative"
            >
                <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Client *</label>

                {{-- Trigger Button --}}
                <button type="button" @click="open = !open"
                        class="relative mt-1.5 w-full flex items-center justify-between px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-sm transition duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500"
                        :class="selectedId ? 'text-slate-800 dark:text-slate-100' : 'text-slate-400'"
                >
                    <span x-text="selectedLabel || '— Select Client —'"></span>
                    <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- Dropdown Panel --}}
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     @click.outside="open = false"
                     class="absolute z-50 top-full mt-1.5 w-full rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700/70 shadow-2xl ring-1 ring-black/5 dark:ring-white/5"
                >
                    {{-- Search Input --}}
                    <div class="p-2">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input x-ref="addSearch" x-model="search" type="text" placeholder="Search clients..."
                                   class="w-full pl-8 pr-3 py-2 text-sm rounded-lg bg-slate-100 dark:bg-slate-800 border-0 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50" />
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="border-t border-slate-100 dark:border-slate-800"></div>

                    {{-- Options List — shows 5 rows, then scrolls --}}
                    <ul class="overflow-y-auto py-1.5" style="max-height: 215px;">
                        <template x-for="c in filtered" :key="c.id">
                            <li @click="select(c)"
                                class="flex items-center gap-2.5 mx-1.5 px-2.5 py-2 rounded-lg cursor-pointer transition-colors duration-100"
                                :class="selectedId === c.id
                                    ? 'bg-indigo-500 text-white'
                                    : 'hover:bg-slate-100 dark:hover:bg-slate-800'"
                            >
                                {{-- Avatar --}}
                                <div class="w-6 h-6 rounded-md flex items-center justify-center shrink-0 text-[10px] font-extrabold"
                                     :class="selectedId === c.id ? 'bg-white/20 text-white' : 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400'"
                                >
                                    <span x-text="c.label.charAt(0).toUpperCase()"></span>
                                </div>
                                {{-- Labels --}}
                                <div class="min-w-0 flex-1">
                                    <div class="text-[13px] font-semibold leading-tight truncate"
                                         :class="selectedId === c.id ? 'text-white' : 'text-slate-800 dark:text-slate-100'"
                                         x-text="c.label"></div>
                                    <div class="text-[11px] leading-tight truncate mt-px"
                                         :class="selectedId === c.id ? 'text-indigo-100' : 'text-slate-400 dark:text-slate-500'"
                                         x-text="c.sub"></div>
                                </div>
                                {{-- Check Icon --}}
                                <svg x-show="selectedId === c.id" class="w-3.5 h-3.5 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </li>
                        </template>
                        <li x-show="filtered.length === 0" class="px-4 py-4 text-sm text-slate-400 dark:text-slate-500 text-center">
                            <svg class="w-8 h-8 mx-auto mb-1 text-slate-300 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            No clients found
                        </li>
                    </ul>
                </div>

                <x-input-error :messages="$errors->get('client_id')" class="mt-1" />
            </div>

            {{-- Site Name --}}
            <div>
                <label for="add_site_name" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Site Name *</label>
                <input wire:model="site_name" id="add_site_name" type="text" placeholder="e.g. Acme Corp Website"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('site_name')" class="mt-1" />
            </div>

            {{-- URL --}}
            <div>
                <label for="add_url" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Website URL *</label>
                <input wire:model="url" id="add_url" type="url" placeholder="https://example.com"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('url')" class="mt-1" />
            </div>

            {{-- Service Type & Status --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="add_site_type" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Service Type *</label>
                    <select wire:model="site_type" id="add_site_type"
                            class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                        <option value="maintenance"        class="dark:bg-slate-900">Maintenance</option>
                        <option value="design"             class="dark:bg-slate-900">Design</option>
                        <option value="development"        class="dark:bg-slate-900">Development</option>
                        <option value="speed_optimisation" class="dark:bg-slate-900">Speed Optimisation</option>
                        <option value="other"              class="dark:bg-slate-900">Other</option>
                    </select>
                    <x-input-error :messages="$errors->get('site_type')" class="mt-1" />
                </div>
                <div>
                    <label for="add_status" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Status *</label>
                    <select wire:model="status" id="add_status"
                            class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                        <option value="active"    class="dark:bg-slate-900">Active</option>
                        <option value="inactive"  class="dark:bg-slate-900">Inactive</option>
                        <option value="suspended" class="dark:bg-slate-900">Suspended</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>
            </div>

            {{-- ── CMS Login Credentials ─────────────────────────────── --}}
            <div class="pt-1">
                <p class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    CMS / Admin Login Credentials
                </p>
                <div class="space-y-3">
                    {{-- Admin URL --}}
                    <div>
                        <label for="add_admin_url" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Admin Login URL</label>
                        <input wire:model="admin_url" id="add_admin_url" type="url" placeholder="https://example.com/wp-admin"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('admin_url')" class="mt-1" />
                    </div>

                    {{-- Username & Password side-by-side --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="add_admin_username" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Admin Username</label>
                            <input wire:model="admin_username" id="add_admin_username" type="text" autocomplete="new-username" placeholder="admin"
                                   class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                            <x-input-error :messages="$errors->get('admin_username')" class="mt-1" />
                        </div>
                        <div x-data="{ show: false }">
                            <label for="add_admin_password" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Admin Password</label>
                            <div class="relative mt-1.5">
                                <input wire:model="admin_password" id="add_admin_password" :type="show ? 'text' : 'password'" autocomplete="new-password" placeholder="••••••••"
                                       class="block w-full px-4 py-2.5 pr-10 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                                <button type="button" @click="show = !show"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-indigo-500 transition-colors">
                                    <svg x-show="!show" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="show" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('admin_password')" class="mt-1" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Hosting Details ────────────────────────────────────── --}}
            <div class="pt-1">
                <p class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                    </svg>
                    Hosting Details
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="add_hosting_provider" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Hosting Provider</label>
                        <input wire:model="hosting_provider" id="add_hosting_provider" type="text" placeholder="e.g. SiteGround, WP Engine"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('hosting_provider')" class="mt-1" />
                    </div>
                    <div>
                        <label for="add_server_ip" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Server IP</label>
                        <input wire:model="server_ip" id="add_server_ip" type="text" placeholder="e.g. 192.168.1.1"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('server_ip')" class="mt-1" />
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label for="add_notes" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Notes</label>
                <textarea wire:model="notes" id="add_notes" rows="3" placeholder="Any additional notes about this website..."
                          class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150"></textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-1" />
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                <button type="button" @click="$dispatch('close-modal', { name: 'add-website-modal' })"
                        class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all duration-150">
                    Cancel
                </button>
                <x-admin.button type="button" wire:click="saveWebsite" size="sm" variant="primary" wire:loading.attr="disabled" class="space-x-1.5">
                    <svg wire:loading wire:target="saveWebsite" class="animate-spin h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Save Website</span>
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>

    {{-- ═══════════════════════════════════════════════════════════
         EDIT WEBSITE MODAL
    ═══════════════════════════════════════════════════════════ --}}
    <x-admin.modal name="edit-website-modal" title="Edit Website">
        <div class="space-y-4 mt-2">

            {{-- Client Searchable Dropdown (Edit Modal) --}}
            <div
                x-data="{
                    open: false,
                    search: '',
                    selectedId: @entangle('client_id'),
                    selectedLabel: '',
                    clients: {{ Js::from($clients->map(fn($c) => ['id' => $c->id, 'label' => $c->company_name ?: $c->user->name, 'sub' => $c->user->email ?? ''])) }},
                    get filtered() {
                        if (!this.search) return this.clients;
                        const q = this.search.toLowerCase();
                        return this.clients.filter(c =>
                            c.label.toLowerCase().includes(q) || c.sub.toLowerCase().includes(q)
                        );
                    },
                    select(c) {
                        this.selectedId = c.id;
                        this.selectedLabel = c.label;
                        this.search = '';
                        this.open = false;
                        $wire.set('client_id', c.id);
                    },
                    syncLabel() {
                        const found = this.clients.find(c => c.id == this.selectedId);
                        this.selectedLabel = found ? found.label : '';
                    },
                    init() {
                        this.syncLabel();
                        this.$watch('selectedId', () => this.syncLabel());
                        this.$watch('open', v => { if (v) this.$nextTick(() => this.$refs.editSearch.focus()); });
                    }
                }"
                x-on:close-modal.window="open = false"
                class="relative"
            >
                <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Client *</label>

                {{-- Trigger Button --}}
                <button type="button" @click="open = !open"
                        class="relative mt-1.5 w-full flex items-center justify-between px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-sm transition duration-150 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500"
                        :class="selectedId ? 'text-slate-800 dark:text-slate-100' : 'text-slate-400'"
                >
                    <span x-text="selectedLabel || '— Select Client —'"></span>
                    <svg class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" :class="open ? 'rotate-180' : ''"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- Dropdown Panel --}}
                <div x-show="open" x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     @click.outside="open = false"
                     class="absolute z-50 top-full mt-1.5 w-full rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700/70 shadow-2xl ring-1 ring-black/5 dark:ring-white/5"
                >
                    {{-- Search Input --}}
                    <div class="p-2">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input x-ref="editSearch" x-model="search" type="text" placeholder="Search clients..."
                                   class="w-full pl-8 pr-3 py-2 text-sm rounded-lg bg-slate-100 dark:bg-slate-800 border-0 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50" />
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="border-t border-slate-100 dark:border-slate-800"></div>

                    {{-- Options List — shows 5 rows, then scrolls --}}
                    <ul class="overflow-y-auto py-1.5" style="max-height: 215px;">
                        <template x-for="c in filtered" :key="c.id">
                            <li @click="select(c)"
                                class="flex items-center gap-2.5 mx-1.5 px-2.5 py-2 rounded-lg cursor-pointer transition-colors duration-100"
                                :class="selectedId == c.id
                                    ? 'bg-indigo-500 text-white'
                                    : 'hover:bg-slate-100 dark:hover:bg-slate-800'"
                            >
                                {{-- Avatar --}}
                                <div class="w-6 h-6 rounded-md flex items-center justify-center shrink-0 text-[10px] font-extrabold"
                                     :class="selectedId == c.id ? 'bg-white/20 text-white' : 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400'"
                                >
                                    <span x-text="c.label.charAt(0).toUpperCase()"></span>
                                </div>
                                {{-- Labels --}}
                                <div class="min-w-0 flex-1">
                                    <div class="text-[13px] font-semibold leading-tight truncate"
                                         :class="selectedId == c.id ? 'text-white' : 'text-slate-800 dark:text-slate-100'"
                                         x-text="c.label"></div>
                                    <div class="text-[11px] leading-tight truncate mt-px"
                                         :class="selectedId == c.id ? 'text-indigo-100' : 'text-slate-400 dark:text-slate-500'"
                                         x-text="c.sub"></div>
                                </div>
                                {{-- Check Icon --}}
                                <svg x-show="selectedId == c.id" class="w-3.5 h-3.5 text-white shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </li>
                        </template>
                        <li x-show="filtered.length === 0" class="px-4 py-4 text-sm text-slate-400 dark:text-slate-500 text-center">
                            <svg class="w-8 h-8 mx-auto mb-1 text-slate-300 dark:text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            No clients found
                        </li>
                    </ul>
                </div>

                <x-input-error :messages="$errors->get('client_id')" class="mt-1" />
            </div>

            {{-- Site Name --}}
            <div>
                <label for="edit_site_name" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Site Name *</label>
                <input wire:model="site_name" id="edit_site_name" type="text"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('site_name')" class="mt-1" />
            </div>

            {{-- URL --}}
            <div>
                <label for="edit_url" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Website URL *</label>
                <input wire:model="url" id="edit_url" type="url"
                       class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                <x-input-error :messages="$errors->get('url')" class="mt-1" />
            </div>

            {{-- Service Type & Status --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="edit_site_type" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Service Type *</label>
                    <select wire:model="site_type" id="edit_site_type"
                            class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                        <option value="maintenance"        class="dark:bg-slate-900">Maintenance</option>
                        <option value="design"             class="dark:bg-slate-900">Design</option>
                        <option value="development"        class="dark:bg-slate-900">Development</option>
                        <option value="speed_optimisation" class="dark:bg-slate-900">Speed Optimisation</option>
                        <option value="other"              class="dark:bg-slate-900">Other</option>
                    </select>
                    <x-input-error :messages="$errors->get('site_type')" class="mt-1" />
                </div>
                <div>
                    <label for="edit_status" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Status *</label>
                    <select wire:model="status" id="edit_status"
                            class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/45 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150">
                        <option value="active"    class="dark:bg-slate-900">Active</option>
                        <option value="inactive"  class="dark:bg-slate-900">Inactive</option>
                        <option value="suspended" class="dark:bg-slate-900">Suspended</option>
                    </select>
                    <x-input-error :messages="$errors->get('status')" class="mt-1" />
                </div>
            </div>

            {{-- CMS Login Credentials --}}
            <div class="pt-1">
                <p class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    CMS / Admin Login Credentials
                </p>
                <div class="space-y-3">
                    <div>
                        <label for="edit_admin_url" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Admin Login URL</label>
                        <input wire:model="admin_url" id="edit_admin_url" type="url" placeholder="https://example.com/wp-admin"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('admin_url')" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="edit_admin_username" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Admin Username</label>
                            <input wire:model="admin_username" id="edit_admin_username" type="text" autocomplete="off" placeholder="admin"
                                   class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                            <x-input-error :messages="$errors->get('admin_username')" class="mt-1" />
                        </div>
                        <div x-data="{ show: false }">
                            <label for="edit_admin_password" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                                Admin Password
                                @if($passwordIsSet)
                                    <span class="ml-1 text-emerald-500 font-bold">(set)</span>
                                @endif
                            </label>
                            <div class="relative mt-1.5">
                                <input wire:model="admin_password" id="edit_admin_password" :type="show ? 'text' : 'password'"
                                       autocomplete="new-password"
                                       placeholder="{{ $passwordIsSet ? 'Leave blank to keep current' : '••••••••' }}"
                                       class="block w-full px-4 py-2.5 pr-10 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                                <button type="button" @click="show = !show"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-indigo-500 transition-colors">
                                    <svg x-show="!show" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="show" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('admin_password')" class="mt-1" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Hosting Details --}}
            <div class="pt-1">
                <p class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                    </svg>
                    Hosting Details
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="edit_hosting_provider" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Hosting Provider</label>
                        <input wire:model="hosting_provider" id="edit_hosting_provider" type="text" placeholder="e.g. SiteGround"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('hosting_provider')" class="mt-1" />
                    </div>
                    <div>
                        <label for="edit_server_ip" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Server IP</label>
                        <input wire:model="server_ip" id="edit_server_ip" type="text" placeholder="e.g. 192.168.1.1"
                               class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150" />
                        <x-input-error :messages="$errors->get('server_ip')" class="mt-1" />
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label for="edit_notes" class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Notes</label>
                <textarea wire:model="notes" id="edit_notes" rows="3" placeholder="Any additional notes..."
                          class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50/50 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 text-sm transition duration-150"></textarea>
                <x-input-error :messages="$errors->get('notes')" class="mt-1" />
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200/40 dark:border-slate-800/30">
                <button type="button" @click="$dispatch('close-modal', { name: 'edit-website-modal' })"
                        class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all duration-150">
                    Cancel
                </button>
                <x-admin.button type="button" wire:click="updateWebsite" size="sm" variant="primary" wire:loading.attr="disabled" class="space-x-1.5">
                    <svg wire:loading wire:target="updateWebsite" class="animate-spin h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Update Website</span>
                </x-admin.button>
            </div>
        </div>
    </x-admin.modal>
</div>
