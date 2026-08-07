@section('page_title', 'Create Maintenance Report')

<div>
    {{-- Breadcrumbs --}}
    <x-admin.breadcrumbs :items="['Maintenance Reports' => route('admin.maintenance'), 'Create' => null]" />

    {{-- Error alerts --}}
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

    <form wire:submit.prevent="saveReport" class="space-y-6">
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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                               class="block w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/55" />
                        
                        <!-- Toggle arrow -->
                        <button type="button" x-on:click="open = !open" class="absolute right-3 top-3.5 text-slate-400 hover:text-slate-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>

                    <!-- Dropdown List -->
                    <div x-show="open" 
                         x-transition
                         class="absolute z-[9999] w-full mt-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-2xl max-h-60 overflow-y-auto scrollbar-thin">
                        <template x-for="c in clients" :key="c.id">
                            <div x-show="search === '' || c.name.toLowerCase().includes(search.toLowerCase())"
                                 x-on:click="select(c.id, c.name)"
                                 class="px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-indigo-500 hover:text-white cursor-pointer transition-colors font-medium"
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
                    <select wire:model="developer_id" class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50">
                        <option value="">Select Developer</option>
                        @foreach($developers as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('developer_id')" class="mt-1" />
                </div>

                {{-- Month --}}
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Maintenance Month</label>
                    <input wire:model="maintenance_month" type="month"
                           class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50" />
                    <x-input-error :messages="$errors->get('maintenance_month')" class="mt-1" />
                </div>

                {{-- Date --}}
                <div>
                    <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Maintenance Date</label>
                    <input wire:model="maintenance_date" type="date"
                           class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/50" />
                    <x-input-error :messages="$errors->get('maintenance_date')" class="mt-1" />
                </div>
            </div>
        </x-admin.card>

        {{-- Section 2: Environment Configs (WP, PHP, Theme) --}}
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

        {{-- Section 5: Plugins (Dynamic Table) --}}
        <x-admin.card>
            <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">5. Plugin Updates</h3>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500">Record all plugins upgraded or requiring attention</p>
                </div>
                <button type="button" wire:click="addPluginField"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-xs font-bold hover:bg-indigo-100 dark:hover:bg-indigo-500/20 active:scale-95 transition-all duration-150">
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
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                <div class="col-span-1 md:col-span-4">
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

        {{-- Section 9: Backup & Section 10: Support Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Backup --}}
            <x-admin.card>
                <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">9. System Backups</h3>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800/50">
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Backup Successfully Completed?</span>
                        <input type="checkbox" wire:model="backup_completed" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Backup Date</label>
                        <input wire:model="backup_date" type="date" class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Backup Remote Location</label>
                        <input wire:model="backup_location" type="text" placeholder="e.g. AWS S3, Google Drive" class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Backup Notes</label>
                        <textarea wire:model="backup_notes" rows="2" placeholder="Backup size and verification notes..." class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs w-full"></textarea>
                    </div>
                </div>
            </x-admin.card>

            {{-- Support Summary --}}
            <x-admin.card>
                <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">10. Support Summary</h3>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Tickets Completed</label>
                            <input wire:model="support_tickets_completed" type="number" class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm" />
                        </div>
                        <div>
                            <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Tickets Pending</label>
                            <input wire:model="support_tickets_pending" type="number" class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Time Spent</label>
                        <input wire:model="support_time_spent" type="text" placeholder="e.g. 4.5 hours" class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Completion Date</label>
                        <input wire:model="support_completion_date" type="date" class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Summary of Work Completed</label>
                        <textarea wire:model="support_work_summary" rows="2" placeholder="Describe the manual fixes, improvements or changes completed..." class="block mt-1.5 w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs w-full"></textarea>
                    </div>
                </div>
            </x-admin.card>
        </div>

        {{-- Section 11 & 12: Internal Notes vs Client Summary --}}
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
                <textarea wire:model="client_summary" rows="4" placeholder="e.g. WordPress updated successfully. 12 plugins updated. Website security verified. Backup created. Performance verified." class="block mt-1.5 w-full px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-sm w-full"></textarea>
            </x-admin.card>
        </div>

        {{-- Section 13: Attachments --}}
        <x-admin.card>
            <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">13. Upload Attachments</h3>
                <p class="text-[10px] text-slate-400 dark:text-slate-500">Upload screenshot images, PDF checks, or speed reports (Max 10MB per file)</p>
            </div>
            <input type="file" wire:model="attachments" multiple class="block text-xs text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-950/20 file:text-indigo-600 dark:file:text-indigo-400 file:cursor-pointer" />
            <div wire:loading wire:target="attachments" class="text-xs text-indigo-500 font-bold mt-2">Uploading attachments...</div>
            <x-input-error :messages="$errors->get('attachments.*')" class="mt-1" />

            @if(!empty($attachments))
                <div class="mt-4 space-y-2">
                    <h4 class="text-[10px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Selected Files to Upload:</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($attachments as $file)
                            @php
                                $isImg = in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                            @endphp
                            <div class="flex flex-col gap-2 p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/50">
                                @if($isImg)
                                    <div class="rounded-lg overflow-hidden border border-slate-100 dark:border-slate-800 bg-white">
                                        <img src="{{ $file->temporaryUrl() }}" class="w-full h-32 object-cover" />
                                    </div>
                                @endif
                                <div class="flex items-center justify-between gap-2 mt-1">
                                    <span class="text-xs text-slate-700 dark:text-slate-300 font-semibold truncate pr-2" title="{{ $file->getClientOriginalName() }}">{{ $file->getClientOriginalName() }}</span>
                                    <span class="text-[10px] text-slate-400 shrink-0">({{ round($file->getSize() / 1024, 1) }} KB)</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </x-admin.card>

        {{-- Submit Buttons --}}
        <div class="flex items-center justify-end gap-3 pb-8">
            <a href="{{ route('admin.maintenance') }}" class="px-5 py-3 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-xs rounded-xl border border-slate-200/50 dark:border-slate-800/50 active:scale-95 transition-all duration-150">
                Cancel
            </a>
            
            <button type="submit" class="px-5 py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white text-xs font-semibold shadow-sm transition-all duration-150 active:scale-95">
                Save Report
            </button>
        </div>
    </form>
</div>
