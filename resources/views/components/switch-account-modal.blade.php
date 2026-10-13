<div x-data="{
        show: false,
        name: '',
        email: '',
        company: '',
        initials: '',
        currentInitials: '{{ auth()->user() ? auth()->user()->getInitials() : 'AD' }}',
        currentRole: '{{ auth()->user() && auth()->user()->staff ? 'Staff' : 'Admin' }}',
        targetRole: 'client',
        url: '',
        open(detail) {
            this.name = detail.name || '';
            this.email = detail.email || '';
            this.company = detail.company || '';
            this.initials = detail.initials || (this.name ? this.name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase() : 'CL');
            this.targetRole = detail.role || 'client';
            this.url = detail.url || '#';
            this.show = true;
            document.body.classList.add('overflow-hidden');
        },
        close() {
            this.show = false;
            document.body.classList.remove('overflow-hidden');
        }
     }"
     x-show="show"
     x-on:open-switch-account-modal.window="open($event.detail)"
     x-on:keydown.escape.window="close()"
     style="display: none;"
     class="fixed inset-0 z-[9999] flex items-center justify-center p-4">

    <!-- Backdrop Overlay -->
    <div x-show="show"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="close()"
         class="fixed inset-0 bg-[#0f172a]/70 backdrop-blur-sm"></div>

    <!-- Centered Modal Card matching screenshot -->
    <div x-show="show"
         x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-3"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-3"
         class="relative w-full mx-auto bg-white dark:bg-slate-900 rounded-[24px] shadow-[0_25px_60px_-15px_rgba(0,0,0,0.4)] border border-slate-100 dark:border-slate-800 text-center z-10 overflow-hidden"
         style="max-width: 360px;">

        <!-- Teal / Blue-green Gradient Header -->
        <div class="px-6 pt-7 pb-6 bg-gradient-to-b from-[#1b6b7a] via-[#1b6b7a] to-[#257f8f] dark:from-[#134e59] dark:to-[#1b6b7a] text-white">
            <!-- Avatars with switch double arrow in between -->
            <div class="flex items-center justify-center gap-3 mb-2.5">
                <!-- Current user avatar (translucent teal circle) -->
                <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm border border-white/25 flex items-center justify-center text-xs font-bold text-white shadow-inner"
                     x-text="currentInitials">
                    AD
                </div>

                <!-- Switch arrow icon -->
                <div class="text-white/80">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                        <path d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </div>

                <!-- Target user avatar (white circle) -->
                <div class="w-10 h-10 rounded-full bg-white text-[#1b6b7a] font-bold text-xs flex items-center justify-center shadow-md"
                     x-text="initials">
                    CM
                </div>
            </div>

            <!-- Subtitle below avatars -->
            <p class="text-[11px] font-medium text-white/90 tracking-wide">
                <span x-text="currentRole">Admin</span> to <span x-text="targetRole">client</span> session
            </p>
        </div>

        <!-- Modal Body -->
        <div class="p-5 sm:p-6 text-left">
            <!-- Target User Info Box (rounded rectangle) -->
            <div class="border border-slate-200 dark:border-slate-800 rounded-2xl p-3 sm:p-3.5 flex items-center gap-3 bg-white dark:bg-slate-900 shadow-sm">
                <!-- Circle avatar -->
                <div class="w-11 h-11 rounded-full bg-[#e3f4f6] dark:bg-teal-950/50 text-[#1b6b7a] dark:text-teal-400 font-bold text-xs flex items-center justify-center shrink-0 border border-teal-100 dark:border-teal-900/40"
                     x-text="initials">
                    CM
                </div>

                <!-- Name, Email, Company -->
                <div class="min-w-0 flex-1">
                    <h4 class="font-bold text-slate-900 dark:text-white text-sm leading-tight truncate" x-text="name">
                        Christine Mullen
                    </h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate mt-0.5" x-text="email" x-show="email">
                        christine@aspirelocalmarketing.com
                    </p>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 truncate" x-text="company" x-show="company">
                        Aspiredigitalsolutions
                    </p>
                </div>
            </div>

            <!-- Question and subtext -->
            <div class="text-center mt-5">
                <h3 class="text-base sm:text-[17px] font-bold text-slate-900 dark:text-white">
                    Login as this <span x-text="targetRole">client</span>?
                </h3>
                <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mt-1 leading-normal">
                    You'll see the portal exactly as this <span x-text="targetRole">client</span> sees it.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex items-center gap-2.5">
                <button type="button"
                        @click="close()"
                        class="flex-1 py-2.5 px-3 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 transition active:scale-[0.98] text-center">
                    Cancel
                </button>

                <a :href="url"
                   class="flex-1 py-2.5 px-3 rounded-xl text-xs sm:text-sm font-semibold text-white bg-[#1b6b7a] hover:bg-[#165662] active:bg-[#11434c] shadow-sm transition active:scale-[0.98] inline-flex items-center justify-center gap-1.5 text-center">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <span>Yes, Login</span>
                </a>
            </div>
        </div>
    </div>
</div>
