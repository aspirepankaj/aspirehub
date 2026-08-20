<div x-data="{ open: false }" @click.outside="open = false" class="relative">
    <!-- Bell Button -->
    <button @click="open = !open" 
            type="button"
            class="relative p-2.5 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100/60 dark:hover:bg-slate-900/40 border border-transparent hover:border-slate-200/40 dark:hover:border-slate-800/40 transition-all duration-200 focus:outline-none" 
            title="View Notifications">
        
        <!-- Glowing Unread Count Badge -->
        @if($unreadCount > 0)
            <span class="absolute top-1.5 right-1.5 flex h-4 w-4 items-center justify-center">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pink-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-4 min-w-[16px] px-1 bg-gradient-to-r from-pink-500 to-rose-600 text-white text-[9px] font-black items-center justify-center shadow-sm">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
            </span>
        @endif

        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
    </button>

    <!-- Dropdown Panel -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-2"
         class="absolute right-0 mt-3 w-80 sm:w-96 rounded-2xl bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-slate-200/80 dark:border-slate-800/80 shadow-2xl z-[9999] overflow-hidden"
         style="display: none;">
        
        <!-- Header -->
        <div class="p-4 border-b border-slate-100 dark:border-slate-800/80 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-800 dark:text-slate-200">
                    Notifications
                </h3>
                @if($unreadCount > 0)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                        {{ $unreadCount }} new
                    </span>
                @endif
            </div>

            @if($unreadCount > 0)
                <button type="button" wire:click="markAllAsRead" class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                    Mark all read
                </button>
            @endif
        </div>

        <!-- Notifications List -->
        <div class="max-h-[360px] overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800/60">
            @forelse($notifications as $notif)
                <div wire:click="markAsRead({{ $notif->id }})" 
                     class="p-3.5 flex items-start gap-3 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/30 transition-colors cursor-pointer {{ !$notif->is_read ? 'bg-indigo-50/20 dark:bg-indigo-950/15' : '' }}">
                    
                    <!-- Sender Icon Badge / Avatar -->
                    <div class="shrink-0 mt-0.5">
                        @php
                            $senderUser = $notif->senderUser;
                            $avatarUrl = $senderUser?->getProfileImageUrl();
                            $initials = $senderUser?->getInitials() ?? strtoupper(substr($notif->sender_name ?? 'U', 0, 2));
                            $badgeColor = match($notif->sender_type) {
                                'admin' => 'bg-purple-600 text-white',
                                'staff' => 'bg-blue-600 text-white',
                                default => 'bg-emerald-600 text-white',
                            };
                        @endphp
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $notif->sender_name }}" class="w-8 h-8 rounded-xl object-cover shadow-sm border border-slate-200 dark:border-slate-700" onerror="this.outerHTML=`<div class='w-8 h-8 rounded-xl {{ $badgeColor }} flex items-center justify-center text-xs font-bold shadow-sm'>{{ $initials }}</div>`" />
                        @else
                            <div class="w-8 h-8 rounded-xl {{ $badgeColor }} flex items-center justify-center text-xs font-bold shadow-sm">
                                {{ $initials }}
                            </div>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1 mb-0.5">
                            <span class="text-xs font-extrabold text-slate-900 dark:text-white truncate">
                                {{ $notif->title }}
                            </span>
                            @if(!$notif->is_read)
                                <span class="w-2 h-2 rounded-full bg-pink-500 shrink-0"></span>
                            @endif
                        </div>
                        
                        <p class="text-[11px] text-slate-600 dark:text-slate-400 line-clamp-2 leading-tight mb-1">
                            {{ $notif->message }}
                        </p>

                        <div class="flex items-center justify-between text-[9px] text-slate-400">
                            <span class="font-semibold text-indigo-600 dark:text-indigo-400 uppercase">
                                {{ $notif->sender_name }} ({{ ucfirst($notif->sender_type) }})
                            </span>
                            <span>{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-700 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="text-xs font-bold text-slate-500 dark:text-slate-400">No notifications yet</p>
                </div>
            @endforelse
        </div>

        <!-- Footer Link -->
        <div class="p-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/80 dark:bg-slate-900/80 text-center">
            <a href="{{ $viewAllUrl }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors inline-flex items-center gap-1">
                View All Notifications
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </div>
</div>
