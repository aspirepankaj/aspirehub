<div x-data="{
    open: false,
    dateFrom: @entangle('dateFrom').live,
    dateTo: @entangle('dateTo').live,
    compareDateFrom: @entangle('compareDateFrom').live,
    compareDateTo: @entangle('compareDateTo').live,
    includeTodayLive: @entangle('includeToday').live,
    compareFormatLive: @entangle('compareFormat').live,
    
    minDate: '{{ $minDateBound ?? \Carbon\Carbon::now()->subDays(365)->format('Y-m-d') }}',
    maxDate: '{{ $maxDateBound ?? \Carbon\Carbon::now()->format('Y-m-d') }}',
    
    tempStart: '',
    tempEnd: '',
    tempCompareStart: '',
    tempCompareEnd: '',
    includeToday: false,
    comparePreset: 'last_period',
    format: 'percentage',
    
    hoverDate: null,
    selectedPreset: 'last_30',
    selectingStep: 0,
    viewDate: new Date(),

    init() {
        this.tempStart = this.dateFrom || '';
        this.tempEnd = this.dateTo || '';
        this.tempCompareStart = this.compareDateFrom || '';
        this.tempCompareEnd = this.compareDateTo || '';
        this.includeToday = this.includeTodayLive || false;
        this.format = this.compareFormatLive || 'percentage';
        
        this.syncPresetFromDates();
        if (this.tempStart) {
            this.viewDate = new Date(this.tempStart);
        }
        
        this.$watch('tempStart', value => this.calculateCompareDates());
        this.$watch('tempEnd', value => this.calculateCompareDates());
        this.$watch('comparePreset', value => this.calculateCompareDates());
        this.$watch('includeToday', value => {
            if(value && this.tempEnd) {
                const today = new Date();
                this.tempEnd = this.formatDateObj(today);
            }
        });
    },

    togglePopover() {
        if (!this.open) {
            this.tempStart = this.dateFrom || '';
            this.tempEnd = this.dateTo || '';
            this.tempCompareStart = this.compareDateFrom || '';
            this.tempCompareEnd = this.compareDateTo || '';
            this.includeToday = this.includeTodayLive || false;
            this.format = this.compareFormatLive || 'percentage';
            
            this.syncPresetFromDates();
            if (this.tempStart) {
                this.viewDate = new Date(this.tempStart);
            }
            this.selectingStep = 0;
        }
        this.open = !this.open;
    },

    closePopover() {
        this.open = false;
        this.hoverDate = null;
        this.selectingStep = 0;
    },

    getVisibleMonths() {
        const months = [];
        const d1 = new Date(this.viewDate.getFullYear(), this.viewDate.getMonth() - 1, 1);
        const d2 = new Date(this.viewDate.getFullYear(), this.viewDate.getMonth(), 1);

        [d1, d2].forEach(dt => {
            const year = dt.getFullYear();
            const monthNum = dt.getMonth() + 1;
            const monthName = dt.toLocaleString('default', { month: 'short' });
            const firstDayIndex = new Date(year, dt.getMonth(), 1).getDay();
            const daysInMonth = new Date(year, dt.getMonth() + 1, 0).getDate();

            months.push({
                year,
                monthNum,
                name: monthName,
                blankDays: firstDayIndex,
                daysInMonth: Array.from({ length: daysInMonth }, (_, i) => i + 1)
            });
        });

        return months;
    },

    prevMonth() {
        this.viewDate = new Date(this.viewDate.getFullYear(), this.viewDate.getMonth() - 1, 1);
    },

    nextMonth() {
        this.viewDate = new Date(this.viewDate.getFullYear(), this.viewDate.getMonth() + 1, 1);
    },

    formatIso(y, m, d) {
        const mm = String(m).padStart(2, '0');
        const dd = String(d).padStart(2, '0');
        return `${y}-${mm}-${dd}`;
    },
    
    formatDateObj(dt) {
        const y = dt.getFullYear();
        const m = String(dt.getMonth() + 1).padStart(2, '0');
        const d = String(dt.getDate()).padStart(2, '0');
        return `${y}-${m}-${d}`;
    },

    selectCalendarDate(y, m, d) {
        const iso = this.formatIso(y, m, d);
        if (iso < this.minDate || iso > this.maxDate) return;

        if (this.selectingStep === 0) {
            this.tempStart = iso;
            this.tempEnd = iso;
            this.selectingStep = 1;
        } else {
            if (iso < this.tempStart) {
                this.tempEnd = this.tempStart;
                this.tempStart = iso;
            } else {
                this.tempEnd = iso;
            }
            this.selectingStep = 0;
        }
        this.selectedPreset = 'custom';
        this.includeToday = false;
    },

    hoverCalendarDate(y, m, d) {
        if (this.selectingStep === 1) {
            this.hoverDate = this.formatIso(y, m, d);
        }
    },

    getDayStyles(y, m, d) {
        const iso = this.formatIso(y, m, d);
        if (iso < this.minDate || iso > this.maxDate) return '';

        const activeEnd = (this.selectingStep === 1 && this.hoverDate) ? this.hoverDate : this.tempEnd;
        const rangeMin = (this.tempStart && activeEnd) ? (this.tempStart < activeEnd ? this.tempStart : activeEnd) : null;
        const rangeMax = (this.tempStart && activeEnd) ? (this.tempStart < activeEnd ? activeEnd : this.tempStart) : null;

        const isStart = (iso === this.tempStart);
        const isEnd = (iso === this.tempEnd || (this.selectingStep === 1 && iso === this.hoverDate));

        if (isStart || isEnd) {
            return 'background-color: #135266; color: #ffffff; font-weight: bold; border-radius: 4px;';
        }

        if (rangeMin && rangeMax && iso > rangeMin && iso < rangeMax) {
            return 'background-color: #e2e8f0; color: #1e293b; border-radius: 0; width: 100%;';
        }

        return '';
    },

    getDayClasses(y, m, d) {
        const iso = this.formatIso(y, m, d);
        if (iso < this.minDate || iso > this.maxDate) {
            return 'text-slate-300 dark:text-slate-600 pointer-events-none opacity-40';
        }
        return 'hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-sm cursor-pointer';
    },

    applyPreset(preset) {
        const today = new Date();
        
        let start = new Date();
        let end = new Date();
        
        if (!this.includeToday) {
            end.setDate(today.getDate() - 1);
        }

        if (preset === 'last_7') {
            start.setDate(end.getDate() - 6); 
        } else if (preset === 'last_28') {
            start.setDate(end.getDate() - 27);
        } else if (preset === 'last_30') {
            start.setDate(end.getDate() - 29);
        } else if (preset === 'last_90') {
            start.setDate(end.getDate() - 89);
        } else if (preset === 'this_month') {
            start = new Date(today.getFullYear(), today.getMonth(), 1);
            if (this.includeToday) {
                end = new Date();
            } else {
                end = new Date();
                end.setDate(today.getDate() - 1);
            }
        } else if (preset === 'last_month') {
            start = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            end = new Date(today.getFullYear(), today.getMonth(), 0);
        } else {
            return;
        }

        this.tempStart = this.formatDateObj(start);
        this.tempEnd = this.formatDateObj(end);
        this.selectedPreset = preset;
        this.viewDate = new Date(start);
    },

    syncPresetFromDates() {
        if (!this.tempStart || !this.tempEnd) {
            this.selectedPreset = 'last_30';
            return;
        }
        const dStart = new Date(this.tempStart);
        const dEnd = new Date(this.tempEnd);
        const diffDays = Math.round((dEnd - dStart) / (1000 * 60 * 60 * 24)) + 1;

        if (diffDays === 7) this.selectedPreset = 'last_7';
        else if (diffDays === 28) this.selectedPreset = 'last_28';
        else if (diffDays === 30) this.selectedPreset = 'last_30';
        else if (diffDays === 90) this.selectedPreset = 'last_90';
        else this.selectedPreset = 'custom';
    },

    calculateCompareDates() {
        if (!this.tempStart || !this.tempEnd) return;
        
        if (this.comparePreset === 'custom') return;
        
        const dStart = new Date(this.tempStart);
        const dEnd = new Date(this.tempEnd);
        const diffDays = Math.round((dEnd - dStart) / (1000 * 60 * 60 * 24)) + 1;
        
        let cStart = new Date();
        let cEnd = new Date();
        
        if (this.comparePreset === 'last_period') {
            cEnd = new Date(dStart);
            cEnd.setDate(cEnd.getDate() - 1);
            
            cStart = new Date(cEnd);
            cStart.setDate(cStart.getDate() - (diffDays - 1));
        } else if (this.comparePreset === 'previous_year') {
            cStart = new Date(dStart);
            cStart.setFullYear(cStart.getFullYear() - 1);
            
            cEnd = new Date(dEnd);
            cEnd.setFullYear(cEnd.getFullYear() - 1);
        }
        
        this.tempCompareStart = this.formatDateObj(cStart);
        this.tempCompareEnd = this.formatDateObj(cEnd);
    },

    getPresetLabel() {
        const map = {
            'last_7': 'Last 7 days',
            'last_28': 'Last 28 days',
            'last_30': 'Last 30 days',
            'last_90': 'Last 90 days',
            'this_month': 'This Month',
            'last_month': 'Last Month',
            'custom': 'Custom'
        };
        return map[this.selectedPreset] || 'Custom';
    },

    formatDateRangeDisplay() {
        if (!this.tempStart || !this.tempEnd) return 'Select Date Range';
        const parseShort = (str) => {
            const parts = str.split('-');
            if (parts.length < 3) return str;
            const dt = new Date(parts[0], parts[1] - 1, parts[2]);
            return dt.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
        };
        return `${parseShort(this.tempStart)} – ${parseShort(this.tempEnd)}`;
    },

    applySelection() {
        if (this.tempStart && this.tempEnd) {
            this.dateFrom = this.tempStart;
            this.dateTo = this.tempEnd;
            this.compareDateFrom = this.tempCompareStart;
            this.compareDateTo = this.tempCompareEnd;
            this.includeTodayLive = this.includeToday;
            this.compareFormatLive = this.format;
            
            $wire.set('dateFrom', this.tempStart);
            $wire.set('dateTo', this.tempEnd);
            $wire.set('compareDateFrom', this.tempCompareStart);
            $wire.set('compareDateTo', this.tempCompareEnd);
            $wire.set('includeToday', this.includeToday);
            $wire.set('compareFormat', this.format);
            $wire.$refresh(); 
        }
        this.closePopover();
    }
}" x-cloak class="relative inline-block text-left">
    
    <!-- Trigger Button (GA4 Style) -->
    <button @click="togglePopover()" type="button" 
            class="inline-flex items-center gap-2.5 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700/80 border border-slate-300 dark:border-slate-600 rounded-md shadow-xs transition-colors duration-150 text-xs text-slate-800 dark:text-slate-200 font-medium">
        <span class="bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 px-2 py-0.5 rounded border border-slate-200 dark:border-slate-700 text-xs font-semibold"
              x-text="getPresetLabel()">
            Last 30 days
        </span>
        <span class="font-semibold text-slate-700 dark:text-slate-200" x-text="formatDateRangeDisplay()"></span>
        <svg class="w-3.5 h-3.5 text-slate-500 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Popover Modal Container (Fixed Centered) -->
    <div x-show="open" class="fixed inset-0 flex items-center justify-center p-4" style="z-index: 100;" x-cloak>
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/30 backdrop-blur-sm" @click="closePopover()"
             x-show="open" x-transition.opacity></div>

        <!-- Modal Dialog -->
        <div class="relative bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden text-slate-800 dark:text-slate-100 z-10 w-full"
             style="max-width: 860px;"
             x-show="open" @click.stop
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
        
        <div class="flex flex-col sm:flex-row w-full h-full">
            
            <!-- Left: Multi-Month Calendar View Grid -->
            <div class="flex-1 p-5 flex flex-col border-b sm:border-b-0 sm:border-r border-slate-200 dark:border-slate-700">
                <div class="flex gap-6 mb-4">
                    <template x-for="(monthObj, mIdx) in getVisibleMonths()" :key="mIdx">
                        <div class="w-1/2">
                            <!-- Month Header with Nav -->
                            <div class="flex items-center justify-between mb-3 px-1">
                                <button type="button" @click="prevMonth()" x-show="mIdx === 0" class="w-6 h-6 flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 rounded-md text-slate-500 text-base font-bold">
                                    ‹
                                </button>
                                <div class="text-xs font-bold text-slate-800 dark:text-slate-200" x-text="monthObj.name + ' ' + monthObj.year"></div>
                                <button type="button" @click="nextMonth()" x-show="mIdx === 1" class="w-6 h-6 flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 rounded-md text-slate-500 text-base font-bold">
                                    ›
                                </button>
                            </div>

                            <!-- Days Header -->
                            <div class="text-center text-xs text-slate-500 mb-2" style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr));">
                                <div>Sun</div><div>Mon</div><div>Tue</div><div>Wed</div><div>Thu</div><div>Fri</div><div>Sat</div>
                            </div>

                            <!-- Calendar Days Grid -->
                            <div class="gap-y-1 text-center text-xs" style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr));">
                                <!-- Blank leading days -->
                                <template x-for="blank in monthObj.blankDays" :key="'b-'+blank">
                                    <div></div>
                                </template>

                                <!-- Month Days -->
                                <template x-for="day in monthObj.daysInMonth" :key="monthObj.year + '-' + monthObj.monthNum + '-' + day">
                                    <div class="py-0.5 flex items-center justify-center cursor-pointer relative"
                                         @click="selectCalendarDate(monthObj.year, monthObj.monthNum, day)"
                                         @mouseenter="hoverCalendarDate(monthObj.year, monthObj.monthNum, day)">
                                        
                                        <div class="w-full h-8 flex items-center justify-center text-xs transition-all"
                                             :class="getDayClasses(monthObj.year, monthObj.monthNum, day)"
                                             :style="getDayStyles(monthObj.year, monthObj.monthNum, day)">
                                            <span x-text="day"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
                
                <div class="mt-auto pt-3 border-t border-slate-100 dark:border-slate-800 text-xs text-slate-500">
                    Dates are shown in <strong class="text-slate-700 dark:text-slate-300">America/Toronto</strong> timezone <a href="#" class="underline hover:text-slate-800 dark:hover:text-slate-200">Change</a>
                </div>
            </div>

            <!-- Right: Settings Sidebar -->
            <div class="w-full p-5 flex flex-col gap-6 bg-slate-50 dark:bg-slate-800/50 border-l border-slate-200 dark:border-slate-700" style="width: 300px; flex-shrink: 0;">
                
                <!-- Date Range -->
                <div>
                    <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm mb-2 tracking-tight">Date Range</h4>
                    <select x-model="selectedPreset" @change="applyPreset($event.target.value)"
                            class="w-full text-sm bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded px-2.5 py-2 text-slate-800 dark:text-slate-200 outline-none mb-3">
                        <option value="last_7">Last 7 Days</option>
                        <option value="last_28">Last 28 Days</option>
                        <option value="last_30">Last 30 Days</option>
                        <option value="last_90">Last 90 Days</option>
                        <option value="this_month">This Month</option>
                        <option value="last_month">Last Month</option>
                        <option value="custom">Custom</option>
                    </select>

                    <div class="flex items-center gap-2">
                        <input type="date" :min="minDate" :max="maxDate" x-model="tempStart" @change="selectedPreset = 'custom'"
                               class="w-1/2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded px-2 py-1.5 text-slate-800 dark:text-slate-100 text-sm outline-none" />
                        <input type="date" :min="minDate" :max="maxDate" x-model="tempEnd" @change="selectedPreset = 'custom'"
                               class="w-1/2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded px-2 py-1.5 text-slate-800 dark:text-slate-100 text-sm outline-none" />
                    </div>
                    
                    <label class="flex items-center gap-2 mt-4 cursor-pointer">
                        <input type="checkbox" x-model="includeToday" class="w-4 h-4 border-slate-300 rounded cursor-pointer" style="color: #135266;">
                        <span class="text-sm text-slate-800 dark:text-slate-200">Include Today</span>
                    </label>
                </div>

                <!-- Compare To -->
                <div>
                    <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm mb-2 tracking-tight">Compare To</h4>
                    <select x-model="comparePreset"
                            class="w-full text-sm bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded px-2.5 py-2 text-slate-800 dark:text-slate-200 outline-none mb-3">
                        <option value="last_period">Last Period</option>
                        <option value="previous_year">Previous Year</option>
                        <option value="custom">Custom</option>
                    </select>

                    <div class="flex items-center gap-2" :class="{'opacity-60 pointer-events-none': comparePreset !== 'custom'}">
                        <input type="date" x-model="tempCompareStart"
                               class="w-1/2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded px-2 py-1.5 text-slate-800 dark:text-slate-100 text-sm outline-none" />
                        <input type="date" x-model="tempCompareEnd"
                               class="w-1/2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded px-2 py-1.5 text-slate-800 dark:text-slate-100 text-sm outline-none" />
                    </div>
                </div>

                <!-- Format -->
                <div>
                    <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm mb-2 tracking-tight flex items-center gap-1.5">
                        Format
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </h4>
                    <div class="flex flex-col gap-2.5 mt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" x-model="format" value="percentage" name="formatToggle" class="w-4 h-4 border-slate-300 cursor-pointer" style="color: #135266;">
                            <span class="text-sm text-slate-600 dark:text-slate-300">Percentage change</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" x-model="format" value="absolute" name="formatToggle" class="w-4 h-4 border-slate-300 cursor-pointer" style="color: #135266;">
                            <span class="text-sm text-slate-600 dark:text-slate-300">Absolute change</span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-auto flex gap-3 pt-4">
                    <button type="button" @click="applySelection()" class="flex-1 text-white text-sm font-medium py-2 rounded transition-colors shadow-sm hover:opacity-90" style="background-color: #135266;">
                        Apply
                    </button>
                    <button type="button" @click="closePopover()" class="flex-1 bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 text-sm font-medium py-2 rounded transition-colors shadow-sm">
                        Cancel
                    </button>
                </div>
            </div>
            
        </div>
    </div>
</div>
</div>
