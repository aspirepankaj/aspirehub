@section('page_title', 'Edit Maintenance Report')

<div>
    {{-- Breadcrumbs --}}
    <x-admin.breadcrumbs :items="['Maintenance Reports' => route('staff.maintenance'), 'Edit Report #' . $reportId => null]" />

    @if ($errors->any())
        <div class="mb-5 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-600 dark:text-red-400 text-xs">
            <h4 class="font-extrabold uppercase tracking-wider mb-2">Please correct the following errors:</h4>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit.prevent="updateReport" class="space-y-6">
        {{-- Section 1: Basic Information --}}
        <x-admin.card class="relative z-30">
            <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">1. Basic Information</h3>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500">Report association details and logging context</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Status:</span>
                    <select wire:model="status" class="px-3 py-1 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-300">
                        <option value="draft">Draft</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Client Searchable Input Dropdown --}}
                <div x-data="{ 
                        open: false, 
                        search: '',
                        clients: {{ Js::from($clients->map(fn($c) => ['id' => $c->id, 'name' => $c->user->name])) }},
                        select(id, name) {
                            this.search = name;
                            $wire.set('client_id', id);
                            this.open = false;
                        },
                        syncSearch() {
                            const val = $wire.get('client_id');
                            if (!val) {
                                this.search = '';
                            } else {
                                const found = this.clients.find(c => c.id == val);
                                this.search = found ? found.name : '';
                            }
                        },
                        init() {
                            this.syncSearch();
                            this.$watch('$wire.client_id', () => this.syncSearch());
                        }
                     }" 
                     class="relative">
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Client</label>
                    <div class="relative mt-1.5">
                        <input type="text" 
                                x-model="search"
                                x-on:focus="open = true"
                                x-on:click.outside="open = false"
                                placeholder="Type to search client..."
                                class="block w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50" />
                        
                        <button type="button" x-on:click="open = !open" class="absolute right-3 top-3.5 text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>

                    <style>
                        .dropdown-hover-item:hover {
                            background-color: #6366f1 !important;
                            color: #ffffff !important;
                        }
                    </style>

                    <!-- Dropdown List -->
                    <div x-show="open" 
                         x-transition
                         class="absolute z-[9999] w-full mt-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-2xl max-h-60 overflow-y-auto scrollbar-thin">
                        <template x-for="c in clients" :key="c.id">
                            <div x-show="search === '' || c.name.toLowerCase().includes(search.toLowerCase())"
                                 x-on:click="select(c.id, c.name)"
                                 class="px-4 py-2 text-sm text-slate-700 dark:text-slate-200 cursor-pointer transition-colors font-medium dropdown-hover-item"
                                 x-text="c.name">
                            </div>
                        </template>
                    </div>
                    <x-input-error :messages="$errors->get('client_id')" class="mt-1" />
                </div>

                {{-- Website --}}
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Website</label>
                    <select wire:model.live="website_id" class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50" {{ !$client_id ? 'disabled' : '' }}>
                        <option value="">Select Website</option>
                        @foreach($websites as $web)
                            <option value="{{ $web->id }}">{{ $web->site_name }} ({{ $web->url }})</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('website_id')" class="mt-1" />
                </div>

                {{-- Developer --}}
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Developer Assigned</label>
                    <select wire:model="developer_id" class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm focus:outline-none" readonly>
                        @foreach($developers as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('developer_id')" class="mt-1" />
                </div>

                {{-- Month & Year Selector --}}
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Month</label>
                        <select wire:model.live="month_select"
                                class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ sprintf('%02d', $m) }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Year</label>
                        <select wire:model.live="year_select"
                                class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                            @foreach(range(date('Y') - 5, date('Y') + 5) as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" wire:model="maintenance_month" />
                </div>
            </div>
        </x-admin.card>

        {{-- Section 2: Environment Configs --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- WordPress Version --}}
            <x-admin.card>
                <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">2. WordPress Core</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Current Version</label>
                        <input wire:model="wp_version_current" type="text" placeholder="e.g. 6.5.2" class="block mt-1.5 w-full px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Latest Version</label>
                        <input wire:model="wp_version_latest" type="text" placeholder="e.g. 6.6.1" class="block mt-1.5 w-full px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm" />
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800/50">
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400">WordPress Updated?</span>
                        <input type="checkbox" wire:model="wp_updated" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Notes</label>
                        <textarea wire:model="wp_notes" rows="2" placeholder="WordPress core status notes..." class="block mt-1.5 w-full px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm"></textarea>
                    </div>
                </div>
            </x-admin.card>

            {{-- PHP --}}
            <x-admin.card>
                <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">3. PHP Environment</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Current Version</label>
                        <input wire:model="php_version_current" type="text" placeholder="e.g. 8.1.28" class="block mt-1.5 w-full px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Recommended Version</label>
                        <input wire:model="php_version_recommended" type="text" placeholder="e.g. 8.2.18" class="block mt-1.5 w-full px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm" />
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800/50">
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400">PHP Updated?</span>
                        <input type="checkbox" wire:model="php_updated" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Notes</label>
                        <textarea wire:model="php_notes" rows="2" placeholder="PHP version upgrade logs..." class="block mt-1.5 w-full px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm"></textarea>
                    </div>
                </div>
            </x-admin.card>

            {{-- Theme --}}
            <x-admin.card>
                <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">4. Active Theme</h3>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Theme Name</label>
                        <input wire:model="theme_name" type="text" placeholder="e.g. Astra, Divi" class="block mt-1.5 w-full px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Theme Version</label>
                        <input wire:model="theme_version" type="text" placeholder="e.g. 4.6.4" class="block mt-1.5 w-full px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm" />
                    </div>
                    <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800/50">
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Theme Updated?</span>
                        <input type="checkbox" wire:model="theme_updated" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Theme Notes</label>
                        <textarea wire:model="theme_notes" rows="2" placeholder="Theme performance or updates notes..." class="block mt-1.5 w-full px-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm"></textarea>
                    </div>
                </div>
            </x-admin.card>
        </div>

        {{-- Section 5: Plugins --}}
        <x-admin.card>
            <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">5. Plugin Updates</h3>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500">Record all plugins upgraded or requiring attention</p>
                </div>
                <button type="button" wire:click="addPluginField"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-xs font-bold hover:bg-indigo-100 dark:hover:bg-indigo-500/20 active:scale-95 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Plugin
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800/70 text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                            <th class="py-2.5 pr-3">Plugin Name</th>
                            <th class="py-2.5 px-3 w-32">Old Version</th>
                            <th class="py-2.5 px-3 w-32">New Version</th>
                            <th class="py-2.5 px-3 w-48">Status</th>
                            <th class="py-2.5 px-3">Notes</th>
                            <th class="py-2.5 pl-3 w-16 text-right">Delete</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/30">
                        @foreach($plugins as $index => $plugin)
                            <tr wire:key="plugin-field-{{ $index }}">
                                <td class="py-2 pr-3">
                                    <input type="text" wire:model="plugins.{{ $index }}.plugin_name" placeholder="e.g. Elementor Pro" required
                                           class="block w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-xs focus:outline-none" />
                                </td>
                                <td class="py-2 px-3">
                                    <input type="text" wire:model="plugins.{{ $index }}.old_version" placeholder="e.g. 3.2.1"
                                           class="block w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-xs focus:outline-none" />
                                </td>
                                <td class="py-2 px-3">
                                    <input type="text" wire:model="plugins.{{ $index }}.new_version" placeholder="e.g. 3.3.0"
                                           class="block w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-xs focus:outline-none" />
                                </td>
                                <td class="py-2 px-3">
                                    <select wire:model="plugins.{{ $index }}.status"
                                            class="block w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-xs focus:outline-none">
                                        <option value="updated">Updated</option>
                                        <option value="failed">Failed</option>
                                        <option value="license_required">License Required</option>
                                    </select>
                                </td>
                                <td class="py-2 px-3">
                                    <input type="text" wire:model="plugins.{{ $index }}.notes" placeholder="Optional notes..."
                                           class="block w-full px-3 py-2 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-xs focus:outline-none" />
                                </td>
                                <td class="py-2 pl-3 text-right">
                                    <button type="button" wire:click="removePluginField({{ $index }})"
                                            class="p-2 text-slate-400 hover:text-red-500 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800/40 transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-admin.card>

        {{-- Section 6: Security Checks --}}
        <x-admin.card>
            <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">6. Security Checks</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Malware Scan</label>
                    <select wire:model="security_malware_scan" class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm">
                        <option value="completed">Completed / Clean</option>
                        <option value="failed">Failed / Threats Detected</option>
                        <option value="not_run">Not Run</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Firewall Status</label>
                    <select wire:model="security_firewall_status" class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="issues">Needs Config</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Security Plugin Status</label>
                    <select wire:model="security_plugin_status" class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm">
                        <option value="active">Active</option>
                        <option value="not_installed">Not Installed</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">SSL Certificate Status</label>
                    <select wire:model="security_ssl_status" class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm">
                        <option value="valid">Valid / Secure</option>
                        <option value="expired">Expired</option>
                        <option value="missing">Missing / Self-Signed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Security Health</label>
                    <select wire:model="security_health" class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm">
                        <option value="Excellent">Excellent</option>
                        <option value="Good">Good</option>
                        <option value="Action Required">Action Required</option>
                        <option value="Critical">Critical</option>
                    </select>
                </div>
                <div class="col-span-1 md:col-span-5">
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Security Notes</label>
                    <textarea wire:model="security_notes" rows="2" placeholder="Security vulnerabilities or scanning observations..." class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm w-full"></textarea>
                </div>
            </div>
        </x-admin.card>

        {{-- Section 7: Health & Section 8: Performance --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Health --}}
            <x-admin.card>
                <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">7. Website Health Score</h3>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Health Score (0-100)</label>
                        <input wire:model="health_score" type="number" class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Critical Issues</label>
                        <input wire:model="health_critical_issues" type="number" class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Warnings</label>
                        <input wire:model="health_warnings" type="number" class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Passed Tests</label>
                        <input wire:model="health_passed_tests" type="number" class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm" />
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Health Notes</label>
                        <textarea wire:model="health_notes" rows="2" placeholder="Site health issues logs..." class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs w-full"></textarea>
                    </div>
                </div>
            </x-admin.card>

            {{-- Performance --}}
            <x-admin.card>
                <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">8. PageSpeed & Vitals</h3>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Desktop Score (0-100)</label>
                        <input wire:model="performance_desktop" type="number" class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Mobile Score (0-100)</label>
                        <input wire:model="performance_mobile" type="number" class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm" />
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Core Web Vitals</label>
                        <select wire:model="performance_core_web_vitals" class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm">
                            <option value="passed">Passed</option>
                            <option value="failed">Failed / Poor</option>
                            <option value="needs_improvement">Needs Improvement</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Performance Notes</label>
                        <textarea wire:model="performance_notes" rows="2" placeholder="LCP, CLS details..." class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs w-full"></textarea>
                    </div>
                </div>
            </x-admin.card>
        </div>



        {{-- Section 11 & 12 --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-admin.card>
                <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">11. Developer Notes (Internal)</h3>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500">Only visible to administrators and staff members</p>
                </div>
                <textarea wire:model="developer_notes" rows="4" placeholder="Enter private details, upcoming warnings or configuration dependencies..." class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm w-full"></textarea>
            </x-admin.card>

            <x-admin.card>
                <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">12. Client Summary</h3>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500">Visible to client in PDF output - use simple, non-technical language</p>
                </div>
                <textarea wire:model="client_summary" rows="4" placeholder="e.g. WordPress updated successfully. 12 plugins updated. Website security verified. Backup created. Performance verified." class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-sm w-full"></textarea>
            </x-admin.card>
        </div>

        {{-- Section 13: Attachments --}}
        <x-admin.card>
            <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">13. Attachments</h3>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Share attachments with client?</span>
                    <button type="button" 
                            wire:click="toggleAttachmentsVisibility"
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $attachments_visible_to_client ? 'bg-indigo-600' : 'bg-slate-200 dark:bg-slate-700' }}">
                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $attachments_visible_to_client ? 'translate-x-5' : 'translate-x-0' }}"></span>
                    </button>
                </div>
            </div>

            {{-- Existing Attachments list --}}
            @if(!empty($existingAttachments))
                <div class="mb-6 space-y-2">
                    <h4 class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Existing Files</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($existingAttachments as $att)
                            @php
                                $ext = pathinfo($att['file_path'], PATHINFO_EXTENSION);
                                $isImg = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                            @endphp
                            <div class="flex flex-col gap-2 p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/50">
                                @if($isImg)
                                    <div class="rounded-lg overflow-hidden border border-slate-100 dark:border-slate-800 bg-white">
                                        <img src="{{ asset('storage/' . $att['file_path']) }}" class="w-full h-32 object-cover" />
                                    </div>
                                @endif
                                <div class="flex items-center justify-between gap-2 mt-1">
                                    <span class="text-xs text-slate-700 dark:text-slate-300 font-semibold truncate pr-2" title="{{ $att['file_name'] }}">{{ $att['file_name'] }}</span>
                                    <button type="button" wire:click="deleteAttachment({{ $att['id'] }})"
                                            wire:confirm="Are you sure you want to delete this attachment permanently?"
                                            class="text-xs font-bold text-red-500 hover:text-red-650 transition shrink-0">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Upload New files --}}
            <div>
                              <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                  <button type="button" @click="Livewire.dispatch('open-media-picker', { field: 'attachments' })" class="shrink-0 inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:text-indigo-400 font-semibold text-xs rounded-lg transition-colors border border-indigo-200 dark:border-indigo-800">
                                      <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                      </svg>
                                      Choose from Media Library
                                  </button>
                                  <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase">OR</span>
                                  <div class="flex items-center justify-center gap-2 flex-1 max-w-lg border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-lg p-2 bg-slate-50 dark:bg-slate-900/50 cursor-text focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                       tabindex="0"
                                       x-data
                                       @paste="
                                          let items = $event.clipboardData.items;
                                          for (let i = 0; i < items.length; i++) {
                                              if (items[i].type.indexOf('image') !== -1) {
                                                  let file = items[i].getAsFile();
                                                  @this.upload('pastedImages', file);
                                              }
                                          }
                                       ">
                                      <div wire:loading wire:target="pastedImages" class="text-xs text-indigo-500 font-bold flex items-center gap-2">
                                          <svg class="animate-spin h-3 w-3 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                          </svg>
                                          Uploading pasted image...
                                      </div>
                                      <div wire:loading.remove wire:target="pastedImages" class="text-xs text-slate-500 dark:text-slate-400">
                                          <span class="font-bold">Click here</span> and press <kbd class="px-1.5 py-0.5 bg-slate-200 dark:bg-slate-800 rounded-md shadow-sm border border-slate-300 dark:border-slate-700">Ctrl+V</kbd> to paste an image
                                      </div>
                                  </div>
                              </div>
                <x-input-error :messages="$errors->get('newAttachments.*')" class="mt-1" />

                @if(!empty($newAttachments))
                    <div class="mt-4 space-y-2">
                        <h4 class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">New Selected Files to Upload:</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($newAttachments as $index => $file)
                                <div class="flex items-center justify-between p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50">
                                    <div class="flex items-center gap-3 overflow-hidden">
                                        <div class="p-2 bg-white dark:bg-slate-800 rounded-lg shadow-sm shrink-0 text-slate-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 truncate" title="{{ $file['name'] }}">
                                            {{ $file['name'] }}
                                        </span>
                                    </div>
                                    <button type="button" wire:click="removeNewAttachment({{ $index }})" class="text-slate-400 hover:text-red-500 transition-colors p-1 shrink-0">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </x-admin.card>

        {{-- Submit Buttons --}}
        <div class="flex items-center justify-end gap-3 pb-8">
            <a href="{{ route('staff.maintenance') }}" class="px-5 py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all">
                Cancel
            </a>
            
            <button type="submit" class="px-5 py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white text-xs font-semibold shadow-sm active:scale-95 transition-all">
                Save Report
            </button>
        </div>
    </form>
</div>
