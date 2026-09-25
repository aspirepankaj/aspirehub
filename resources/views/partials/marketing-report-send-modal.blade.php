@php
    $modalClient = $client ?? $this->client ?? null;
    $modalWebsite = $website ?? $this->website ?? null;

    if ((!$modalClient || !$modalWebsite) && method_exists($this, 'getActiveClientAndWebsite')) {
        [$activeC, $activeW] = $this->getActiveClientAndWebsite();
        $modalClient = $modalClient ?? $activeC;
        $modalWebsite = $modalWebsite ?? $activeW;
    }

    if (!$modalClient && !empty($this->clientId)) {
        $modalClient = \App\Modules\CRM\Clients\Models\Client::with('user')->find($this->clientId);
    }
    if (!$modalWebsite && !empty($this->websiteId)) {
        $modalWebsite = \App\Modules\CRM\Websites\Models\Website::find($this->websiteId);
    }

    $activeFrom = !empty($this->dateFrom) ? $this->dateFrom : ($dateFrom ?? \Carbon\Carbon::now()->subDays(28)->format('Y-m-d'));
    $activeTo = !empty($this->dateTo) ? $this->dateTo : ($dateTo ?? \Carbon\Carbon::now()->subDays(1)->format('Y-m-d'));
    $activeCFrom = !empty($this->compareDateFrom) ? $this->compareDateFrom : ($compareDateFrom ?? '');
    $activeCTo = !empty($this->compareDateTo) ? $this->compareDateTo : ($compareDateTo ?? '');

    $previewRoute = ($modalClient && $modalWebsite) ? route('marketing.report.pdf.preview', [
        'client' => $modalClient->id,
        'website' => $modalWebsite->id,
    ]) : '';
    $downloadRoute = ($modalClient && $modalWebsite) ? route('marketing.report.pdf.download', [
        'client' => $modalClient->id,
        'website' => $modalWebsite->id,
    ]) : '';

    $defaultEmail = !empty($this->reportRecipientEmail) ? $this->reportRecipientEmail : ($modalClient->user->email ?? '');
    $monthName = \Carbon\Carbon::parse(!empty($this->dateFrom) ? $this->dateFrom : now())->format('F Y');
    $defaultSubject = !empty($this->reportEmailSubject) ? $this->reportEmailSubject : ("Monthly SEO & Marketing Report - {$monthName}" . ($modalWebsite ? " - {$modalWebsite->site_name}" : ''));
@endphp

<div data-preview-route="{{ $previewRoute }}"
     data-download-route="{{ $downloadRoute }}"
     x-data="{
    openModal: false,
    isLoadingPdf: false,
    previewUrl: '',
    downloadUrl: '#',
    basePreview: '{{ $previewRoute }}',
    baseDownload: '{{ $downloadRoute }}',
    defaultEmail: '{{ addslashes($defaultEmail) }}',
    defaultSubject: '{{ addslashes($defaultSubject) }}',
    emailInput: '{{ addslashes($defaultEmail) }}',
    subjectInput: '{{ addslashes($defaultSubject) }}',
    messageInput: '',
    dateDisplay: '',
    open() {
        let rootContainer = this.$el ? (this.$el.closest('[data-preview-route]') || this.$el) : null;
        let routePreview = (rootContainer && rootContainer.getAttribute('data-preview-route')) ? rootContainer.getAttribute('data-preview-route') : (this.basePreview || '{{ $previewRoute }}');
        let routeDownload = (rootContainer && rootContainer.getAttribute('data-download-route')) ? rootContainer.getAttribute('data-download-route') : (this.baseDownload || '{{ $downloadRoute }}');

        let from = ($wire && typeof $wire.get === 'function' && $wire.get('dateFrom')) ? $wire.get('dateFrom') : (($wire && $wire.dateFrom) ? $wire.dateFrom : '{{ $activeFrom }}');
        let to = ($wire && typeof $wire.get === 'function' && $wire.get('dateTo')) ? $wire.get('dateTo') : (($wire && $wire.dateTo) ? $wire.dateTo : '{{ $activeTo }}');
        let cfrom = ($wire && typeof $wire.get === 'function' && $wire.get('compareDateFrom')) ? $wire.get('compareDateFrom') : (($wire && $wire.compareDateFrom) ? $wire.compareDateFrom : '{{ $activeCFrom }}');
        let cto = ($wire && typeof $wire.get === 'function' && $wire.get('compareDateTo')) ? $wire.get('compareDateTo') : (($wire && $wire.compareDateTo) ? $wire.compareDateTo : '{{ $activeCTo }}');
        
        let ts = Date.now();
        let query = '?from=' + encodeURIComponent(from) + '&to=' + encodeURIComponent(to) + '&_v=' + ts;
        if (cfrom) query += '&cfrom=' + encodeURIComponent(cfrom);
        if (cto) query += '&cto=' + encodeURIComponent(cto);

        let targetPreview = routePreview ? (routePreview + query) : '#';
        let targetDownload = routeDownload ? (routeDownload + query) : '#';

        this.downloadUrl = targetDownload;
        this.dateDisplay = from + ' - ' + to;

        if (!this.emailInput && this.defaultEmail) {
            this.emailInput = this.defaultEmail;
        }
        if (!this.subjectInput && this.defaultSubject) {
            this.subjectInput = this.defaultSubject;
        }
        
        if ($wire) {
            if (!$wire.reportRecipientEmail && this.emailInput) {
                $wire.set('reportRecipientEmail', this.emailInput, false);
            }
            if (!$wire.reportEmailSubject && this.subjectInput) {
                $wire.set('reportEmailSubject', this.subjectInput, false);
            }
        }

        this.isLoadingPdf = true;
        this.openModal = true;
        this.previewUrl = targetPreview;
    },
    close() {
        this.openModal = false;
        this.previewUrl = '';
    }
}" x-cloak class="inline-block">

    {{-- Trigger Button (100% Pure Client-side Alpine, 0 network requests) --}}
    <button type="button" 
        @click="open()" 
        class="inline-flex items-center gap-2 px-3.5 py-2 text-xs font-bold text-white bg-gradient-to-r from-teal-700 to-[#17475a] hover:from-teal-800 hover:to-[#123644] rounded-xl shadow-sm transition-all duration-150 shrink-0 cursor-pointer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
        <span>Preview & Send Report</span>
    </button>

    {{-- Send Report & Live PDF Preview Modal --}}
    <div x-show="openModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-98"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-98"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
         @keydown.escape.window="close()">
        
        <div class="relative w-full max-w-7xl lg:max-w-[95vw] h-[92vh] max-h-[920px] flex flex-col bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden"
             @click.outside="close()">
            
            {{-- Modal Top Bar --}}
            <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-[#17475a] to-[#1e586f] text-white shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center text-teal-300 font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-white tracking-tight flex items-center gap-2">
                            Monthly SEO & Marketing Report
                            <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded-full bg-white/20 text-teal-100">Live Preview</span>
                        </h3>
                        <p class="text-xs text-teal-100/80">
                            {{ $modalWebsite->site_name ?? 'Website' }} &bull; <span x-text="dateDisplay || '{{ \Carbon\Carbon::parse($activeFrom)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($activeTo)->format('M d, Y') }}'"></span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a :href="previewUrl ? (previewUrl + '&pdf=1') : '#'" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-white/15 hover:bg-white/25 text-white rounded-lg transition" title="Open full screen PDF in new tab">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        <span>Full Screen PDF</span>
                    </a>
                    <button type="button" @click="close()" class="p-1.5 rounded-lg text-white/70 hover:text-white hover:bg-white/15 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Modal Body: 2 Columns (60% PDF Preview, 40% Delivery Form) --}}
            <div class="flex-1 flex flex-col md:flex-row overflow-hidden">
                
                {{-- Left Column: PDF Preview Iframe + Skeleton Loader --}}
                <div class="flex-1 h-full bg-slate-100 dark:bg-slate-950 p-4 flex flex-col overflow-hidden border-r border-slate-200 dark:border-slate-800">
                    <div class="flex items-center justify-between pb-2 px-1 text-xs text-slate-500 dark:text-slate-400">
                        <span class="font-medium flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full" :class="isLoadingPdf ? 'bg-amber-400 animate-ping' : 'bg-emerald-500 animate-pulse'"></span>
                            <span x-text="isLoadingPdf ? 'Generating PDF Report...' : 'Exact PDF Output (In-Memory)'"></span>
                        </span>
                        <span class="text-[11px] text-slate-400" x-show="isLoadingPdf">Loading charts & metrics...</span>
                        <span class="text-[11px] text-slate-400" x-show="!isLoadingPdf">Multi-page SEO Document</span>
                    </div>

                    <div class="flex-1 relative rounded-2xl overflow-hidden border border-slate-300 dark:border-slate-700 bg-white shadow-inner">
                        
                        {{-- Skeleton Loading Placeholder Animation --}}
                        <div x-show="isLoadingPdf" 
                             x-transition:leave="transition ease-out duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="absolute inset-0 z-10 bg-slate-50 dark:bg-slate-900 p-6 flex flex-col justify-between overflow-hidden">
                            
                            <div class="space-y-4 animate-pulse">
                                {{-- Header Bar Skeleton --}}
                                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-28 h-7 rounded-lg bg-slate-300 dark:bg-slate-700"></div>
                                        <div class="w-20 h-5 rounded-full bg-slate-200 dark:bg-slate-800"></div>
                                    </div>
                                    <div class="space-y-1.5 text-right">
                                        <div class="w-32 h-4 rounded bg-slate-300 dark:bg-slate-700 ml-auto"></div>
                                        <div class="w-20 h-3 rounded bg-slate-200 dark:bg-slate-800 ml-auto"></div>
                                    </div>
                                </div>

                                {{-- Metric Cards Skeleton --}}
                                <div class="grid grid-cols-4 gap-3 pt-1">
                                    <div class="h-16 rounded-xl bg-slate-200/80 dark:bg-slate-800 p-2.5 space-y-2">
                                        <div class="w-14 h-2 rounded bg-slate-300 dark:bg-slate-700"></div>
                                        <div class="w-10 h-5 rounded bg-slate-300 dark:bg-slate-600"></div>
                                    </div>
                                    <div class="h-16 rounded-xl bg-slate-200/80 dark:bg-slate-800 p-2.5 space-y-2">
                                        <div class="w-14 h-2 rounded bg-slate-300 dark:bg-slate-700"></div>
                                        <div class="w-12 h-5 rounded bg-slate-300 dark:bg-slate-600"></div>
                                    </div>
                                    <div class="h-16 rounded-xl bg-slate-200/80 dark:bg-slate-800 p-2.5 space-y-2">
                                        <div class="w-14 h-2 rounded bg-slate-300 dark:bg-slate-700"></div>
                                        <div class="w-10 h-5 rounded bg-slate-300 dark:bg-slate-600"></div>
                                    </div>
                                    <div class="h-16 rounded-xl bg-slate-200/80 dark:bg-slate-800 p-2.5 space-y-2">
                                        <div class="w-14 h-2 rounded bg-slate-300 dark:bg-slate-700"></div>
                                        <div class="w-8 h-5 rounded bg-slate-300 dark:bg-slate-600"></div>
                                    </div>
                                </div>

                                {{-- Charts Skeleton --}}
                                <div class="grid grid-cols-2 gap-4 pt-1">
                                    <div class="h-40 rounded-xl bg-slate-200/70 dark:bg-slate-800/80 p-4 flex items-center justify-center">
                                        <div class="w-24 h-24 rounded-full border-8 border-slate-300 dark:border-slate-700"></div>
                                    </div>
                                    <div class="h-40 rounded-xl bg-slate-200/70 dark:bg-slate-800/80 p-4 flex items-end justify-around gap-2">
                                        <div class="w-7 h-16 rounded-t bg-slate-300 dark:bg-slate-700"></div>
                                        <div class="w-7 h-28 rounded-t bg-slate-300 dark:bg-slate-600"></div>
                                        <div class="w-7 h-12 rounded-t bg-slate-300 dark:bg-slate-700"></div>
                                        <div class="w-7 h-24 rounded-t bg-slate-300 dark:bg-slate-600"></div>
                                    </div>
                                </div>

                                {{-- Table Skeleton --}}
                                <div class="space-y-2 pt-1">
                                    <div class="h-4 rounded bg-slate-300 dark:bg-slate-700 w-full"></div>
                                    <div class="h-3 rounded bg-slate-200 dark:bg-slate-800 w-5/6"></div>
                                    <div class="h-3 rounded bg-slate-200 dark:bg-slate-800 w-4/6"></div>
                                    <div class="h-3 rounded bg-slate-200 dark:bg-slate-800 w-3/4"></div>
                                </div>
                            </div>

                            {{-- Centered Floating Loading Badge --}}
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="px-5 py-3 rounded-2xl bg-white/95 dark:bg-slate-800/95 shadow-2xl border border-slate-200 dark:border-slate-700 backdrop-blur-md flex items-center gap-3">
                                    <div class="w-5 h-5 border-2 border-teal-600 border-t-transparent rounded-full animate-spin"></div>
                                    <div class="text-left">
                                        <div class="text-xs font-bold text-slate-800 dark:text-slate-100">Rendering PDF Report...</div>
                                        <div class="text-[10px] text-slate-400">Loading vector charts & metrics</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- PDF Preview Iframe --}}
                        <iframe x-show="previewUrl"
                                :src="previewUrl" 
                                @load="isLoadingPdf = false" 
                                class="w-full h-full border-0 bg-white">
                            <p class="p-4 text-center text-sm text-slate-500">
                                Your browser does not support inline viewing. 
                                <a :href="previewUrl" target="_blank" class="text-teal-600 underline">Click here to open the report.</a>
                            </p>
                        </iframe>
                    </div>
                </div>

                {{-- Right Column: Send Email Form & Direct Download --}}
                <div class="w-full md:w-[380px] lg:w-[420px] h-full flex flex-col bg-white dark:bg-slate-900 overflow-y-auto p-6">
                    
                    <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-1">
                        Send Report to Client
                    </h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-5">
                        Deliver this professional multi-page PDF directly to the client's email inbox.
                    </p>

                    {{-- Status Messages --}}
                    @if(!empty($reportModalSuccessMessage))
                        <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-xs text-emerald-800 dark:text-emerald-300 flex items-start gap-2">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>{{ $reportModalSuccessMessage }}</span>
                        </div>
                    @endif

                    @if(!empty($reportModalErrorMessage))
                        <div class="mb-4 p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-xs text-rose-800 dark:text-rose-300 flex items-start gap-2">
                            <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>{{ $reportModalErrorMessage }}</span>
                        </div>
                    @endif

                    <form wire:submit.prevent="sendMarketingReport" class="space-y-4 flex-1 flex flex-col">
                        {{-- Recipient Email --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                Client Email Address <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" 
                                   x-model="emailInput"
                                   @input="$wire && $wire.set('reportRecipientEmail', emailInput, false)"
                                   wire:model.defer="reportRecipientEmail" 
                                   required
                                   placeholder="client@company.com" 
                                   class="w-full px-3.5 py-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none dark:text-slate-200 font-medium text-slate-800" />
                            @error('reportRecipientEmail') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Subject --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                Email Subject <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   x-model="subjectInput"
                                   @input="$wire && $wire.set('reportEmailSubject', subjectInput, false)"
                                   wire:model.defer="reportEmailSubject" 
                                   required
                                   placeholder="Monthly SEO & Marketing Report..." 
                                   class="w-full px-3.5 py-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none dark:text-slate-200 font-medium text-slate-800" />
                            @error('reportEmailSubject') <span class="text-[11px] text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Personal Message / Executive Note --}}
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                Personal Note / Highlights <span class="text-slate-400 font-normal">(Optional)</span>
                            </label>
                            <textarea x-model="messageInput"
                                      @input="$wire && $wire.set('reportEmailMessage', messageInput, false)"
                                      wire:model.defer="reportEmailMessage" 
                                      rows="3" 
                                      placeholder="Add key observations or congratulations on keyword jumps..." 
                                      class="w-full px-3.5 py-2.5 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:outline-none dark:text-slate-200 resize-none font-normal"></textarea>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-2 mt-auto">
                            <button type="submit" 
                                    wire:loading.attr="disabled"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-700 hover:to-teal-800 rounded-xl shadow-md shadow-teal-900/10 transition-all duration-150 disabled:opacity-50 cursor-pointer">
                                <span wire:loading.remove wire:target="sendMarketingReport" class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                    <span>Send Report to Client</span>
                                </span>
                                <span wire:loading wire:target="sendMarketingReport" class="flex items-center gap-2">
                                    <div class="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                                    <span>Sending Email...</span>
                                </span>
                            </button>

                            <a :href="downloadUrl" 
                               class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold text-slate-700 dark:text-slate-200 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 rounded-xl transition-all duration-150 cursor-pointer">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                <span>Download PDF Report</span>
                            </a>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
