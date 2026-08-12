@section('page_title', 'Maintenance Report #' . $report->id)

<div>
    {{-- Breadcrumbs --}}
    <x-admin.breadcrumbs :items="['Maintenance Reports' => route('staff.maintenance'), 'Report Details' => null]" />

    {{-- Actions bar --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6 bg-white/40 dark:bg-slate-900/40 p-4 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl">
        <div class="flex items-center gap-3">
            <span class="text-xs font-extrabold uppercase tracking-widest text-slate-400">Report Status:</span>
            <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-lg uppercase tracking-wider
                  {{ $report->status === 'completed'
                      ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
                      : 'bg-amber-500/10 text-amber-600 dark:text-amber-400' }}">
                {{ $report->status }}
            </span>
            @if($report->last_sent_at)
                <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-lg uppercase tracking-wider bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    Emailed: {{ $report->last_sent_at->format('d M, Y H:i') }}
                </span>
            @endif
        </div>
        <div class="flex items-center gap-2">
            <button type="button" 
                    wire:click="emailReport({{ $report->id }})"
                    wire:confirm="Send Maintenance Report #{{ $report->id }} to client's email ({{ $report->client->user->email }})?"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition active:scale-95 shadow-sm relative">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>Email Client</span>
                <span wire:loading wire:target="emailReport({{ $report->id }})" class="absolute inset-0 flex items-center justify-center bg-emerald-600 rounded-xl">
                    <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
            <a href="{{ route('staff.maintenance.pdf', $report->id) }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-rose-500 hover:bg-rose-600 text-white text-xs font-semibold transition active:scale-95 shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Generate PDF
            </a>
            <a href="{{ route('staff.maintenance.edit', $report->id) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-500 hover:bg-indigo-650 text-white text-xs font-semibold transition active:scale-95 shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Report
            </a>
        </div>
    </div>

    {{-- Details grid --}}
    <div class="space-y-6">
        {{-- Section 1: Overview card --}}
        <x-admin.card>
            <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Basic Context</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Client</h5>
                    <p class="text-sm font-bold text-slate-800 dark:text-slate-200 mt-1">{{ $report->client->company_name ?: $report->client->user->name }}</p>
                </div>
                <div>
                    <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Website</h5>
                    <p class="text-sm font-bold text-indigo-600 dark:text-indigo-400 mt-1 hover:underline">
                        <a href="{{ $report->website->url }}" target="_blank">{{ $report->website->site_name }}</a>
                    </p>
                </div>
                <div>
                    <h5 class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Maintenance Month</h5>
                    <p class="text-sm font-bold text-slate-800 dark:text-slate-200 mt-1">{{ $report->maintenance_month }}</p>
                </div>
            </div>
        </x-admin.card>

        {{-- WordPress Core, PHP, Theme Info --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- WordPress Version --}}
            <x-admin.card>
                <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">WordPress Core</h3>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-[9px] font-bold uppercase text-slate-400">Current</span>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $report->wp_version_current ?: 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold uppercase text-slate-400">Latest Available</span>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $report->wp_version_latest ?: 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800/50 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500">Core Updated:</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $report->wp_updated ? 'bg-emerald-500/10 text-emerald-600' : 'bg-red-500/10 text-red-600' }}">
                            {{ $report->wp_updated ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    @if($report->wp_notes)
                        <div>
                            <span class="text-[9px] font-bold uppercase text-slate-400">WP Notes</span>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 whitespace-pre-line">{{ $report->wp_notes }}</p>
                        </div>
                    @endif
                </div>
            </x-admin.card>

            {{-- PHP Version --}}
            <x-admin.card>
                <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">PHP Environment</h3>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-[9px] font-bold uppercase text-slate-400">Current</span>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $report->php_version_current ?: 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold uppercase text-slate-400">Recommended</span>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $report->php_version_recommended ?: 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800/50 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500">PHP Updated:</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $report->php_updated ? 'bg-emerald-500/10 text-emerald-600' : 'bg-red-500/10 text-red-600' }}">
                            {{ $report->php_updated ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    @if($report->php_notes)
                        <div>
                            <span class="text-[9px] font-bold uppercase text-slate-400">PHP Notes</span>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 whitespace-pre-line">{{ $report->php_notes }}</p>
                        </div>
                    @endif
                </div>
            </x-admin.card>

            {{-- Theme Version --}}
            <x-admin.card>
                <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Active Theme</h3>
                </div>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <span class="text-[9px] font-bold uppercase text-slate-400">Theme Name</span>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $report->theme_name ?: 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold uppercase text-slate-400">Version</span>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $report->theme_version ?: 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800/50 flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-500">Theme Updated:</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase {{ $report->theme_updated ? 'bg-emerald-500/10 text-emerald-600' : 'bg-red-500/10 text-red-600' }}">
                            {{ $report->theme_updated ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    @if($report->theme_notes)
                        <div>
                            <span class="text-[9px] font-bold uppercase text-slate-400">Theme Notes</span>
                            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 whitespace-pre-line">{{ $report->theme_notes }}</p>
                        </div>
                    @endif
                </div>
            </x-admin.card>
        </div>

        {{-- Plugins Updates Table --}}
        <x-admin.card>
            <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Plugin Upgrades Log</h3>
            </div>
            @if($report->plugins->isEmpty())
                <div class="text-center py-6 text-slate-400 text-xs">No plugin updates recorded.</div>
            @else
                <x-admin.table :headers="['Plugin Name', 'Old Version', 'New Version', 'Update Status', 'Notes']">
                    @foreach($report->plugins as $plugin)
                        <tr>
                            <td class="px-6 py-4 font-bold text-slate-900 dark:text-white text-sm">{{ $plugin->plugin_name }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $plugin->old_version ?: '—' }}</td>
                            <td class="px-6 py-4 text-slate-600 dark:text-slate-400">{{ $plugin->new_version ?: '—' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-lg uppercase tracking-wider
                                      {{ $plugin->status === 'updated' ? 'bg-emerald-500/10 text-emerald-600' : '' }}
                                      {{ $plugin->status === 'failed' ? 'bg-red-500/10 text-red-600' : '' }}
                                      {{ $plugin->status === 'license_required' ? 'bg-amber-500/10 text-amber-600' : '' }}
                                      ">
                                    {{ str_replace('_', ' ', $plugin->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400">{{ $plugin->notes ?: '—' }}</td>
                        </tr>
                    @endforeach
                </x-admin.table>
            @endif
        </x-admin.card>

        {{-- Security Check card --}}
        <x-admin.card>
            <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Security Profile</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-150/40">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Malware Scan</span>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mt-1 uppercase">{{ $report->security_malware_scan }}</p>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-150/40">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Firewall Status</span>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mt-1 uppercase">{{ $report->security_firewall_status }}</p>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-150/40">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Security Plugin</span>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mt-1 uppercase">{{ $report->security_plugin_status }}</p>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-150/40">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">SSL Status</span>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mt-1 uppercase">{{ $report->security_ssl_status }}</p>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-150/40">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Security Health</span>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 mt-1 uppercase">{{ $report->security_health ?: 'Excellent' }}</p>
                </div>
            </div>
            @if($report->security_notes)
                <div>
                    <span class="text-[9px] font-bold uppercase text-slate-400">Security Notes</span>
                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 whitespace-pre-line">{{ $report->security_notes }}</p>
                </div>
            @endif
        </x-admin.card>

        {{-- Speed & Health --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Site Health --}}
            <x-admin.card>
                <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Website Health Checks</h3>
                </div>
                <div class="grid grid-cols-4 gap-3 mb-4">
                    <div class="p-2.5 rounded-xl bg-indigo-50/50 dark:bg-indigo-950/20 text-center border border-indigo-100">
                        <span class="text-[8px] font-bold uppercase text-slate-400">Score</span>
                        <p class="text-lg font-black text-indigo-600">{{ $report->health_score }}</p>
                    </div>
                    <div class="p-2.5 rounded-xl bg-red-50/50 dark:bg-red-950/20 text-center border border-red-100">
                        <span class="text-[8px] font-bold uppercase text-slate-400">Critical</span>
                        <p class="text-lg font-black text-red-600">{{ $report->health_critical_issues }}</p>
                    </div>
                    <div class="p-2.5 rounded-xl bg-amber-50/50 dark:bg-amber-950/20 text-center border border-amber-100">
                        <span class="text-[8px] font-bold uppercase text-slate-400">Warnings</span>
                        <p class="text-lg font-black text-amber-600">{{ $report->health_warnings }}</p>
                    </div>
                    <div class="p-2.5 rounded-xl bg-emerald-50/50 dark:bg-emerald-950/20 text-center border border-emerald-100">
                        <span class="text-[8px] font-bold uppercase text-slate-400">Passed</span>
                        <p class="text-lg font-black text-emerald-600">{{ $report->health_passed_tests }}</p>
                    </div>
                </div>
                @if($report->health_notes)
                    <div>
                        <span class="text-[9px] font-bold uppercase text-slate-400">Health Audit Notes</span>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 whitespace-pre-line">{{ $report->health_notes }}</p>
                    </div>
                @endif
            </x-admin.card>

            {{-- Vitals --}}
            <x-admin.card>
                <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">PageSpeed Vitals</h3>
                </div>
                <div class="grid grid-cols-3 gap-3 mb-4">
                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 text-center border border-slate-100">
                        <span class="text-[8px] font-bold uppercase text-slate-400">Desktop</span>
                        <p class="text-lg font-black text-slate-700 dark:text-slate-300">{{ $report->performance_desktop }}</p>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 text-center border border-slate-100">
                        <span class="text-[8px] font-bold uppercase text-slate-400">Mobile</span>
                        <p class="text-lg font-black text-slate-700 dark:text-slate-300">{{ $report->performance_mobile }}</p>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900 text-center border border-slate-100">
                        <span class="text-[8px] font-bold uppercase text-slate-400">Core Vitals</span>
                        <p class="text-xs font-extrabold uppercase mt-2 text-emerald-600">{{ $report->performance_core_web_vitals }}</p>
                    </div>
                </div>
                @if($report->performance_notes)
                    <div>
                        <span class="text-[9px] font-bold uppercase text-slate-400">Performance Notes</span>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 whitespace-pre-line">{{ $report->performance_notes }}</p>
                    </div>
                @endif
            </x-admin.card>
        </div>



        {{-- Notes section --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <x-admin.card>
                <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Internal Developer Notes</h3>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-400 whitespace-pre-line">{{ $report->developer_notes ?: 'No developer notes provided.' }}</p>
            </x-admin.card>

            <x-admin.card>
                <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Client Facing Summary</h3>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-400 whitespace-pre-line font-medium leading-relaxed">{{ $report->client_summary ?: 'No summary provided.' }}</p>
            </x-admin.card>
        </div>

        {{-- Attachments --}}
        @if($report->attachments->isNotEmpty())
            <x-admin.card>
                <div class="border-b border-slate-100 dark:border-slate-800/60 pb-3 mb-4 flex items-center justify-between">
                    <h3 class="text-sm font-extrabold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Attachments Library</h3>
                    <span class="px-2.5 py-1 rounded-xl text-[9px] font-extrabold uppercase tracking-wider {{ $report->attachments_visible_to_client ? 'bg-emerald-500/10 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400' : 'bg-slate-500/10 text-slate-400 dark:bg-slate-850 dark:text-slate-500' }}">
                        {{ $report->attachments_visible_to_client ? 'Shared with Client' : 'Internal Only' }}
                    </span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($report->attachments as $att)
                        @php
                            $extension = pathinfo($att->file_path, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                        @endphp
                        <div class="flex flex-col gap-2 p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/50">
                            @if($isImage)
                                <div class="relative group rounded-lg overflow-hidden border border-slate-100 dark:border-slate-800 bg-white">
                                    <img src="{{ asset('storage/' . $att->file_path) }}" class="w-full h-32 object-cover" />
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-white text-slate-800 text-xs font-bold shadow hover:bg-slate-100">View Full</a>
                                    </div>
                                </div>
                            @endif
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-xs text-slate-700 dark:text-slate-300 font-semibold truncate pr-2" title="{{ $att->file_name }}">{{ $att->file_name }}</span>
                                <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank" class="p-1.5 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 text-indigo-500 transition shrink-0" title="Download">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-admin.card>
        @endif
    </div>
</div>
