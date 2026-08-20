<div class="space-y-6">
    <!-- Top Bar Header -->
    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-5 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Notifications Center
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                View, manage, and respond to all your support ticket alerts and system notifications.
            </p>
        </div>

        @if($unreadCount > 0)
            <button type="button" wire:click="markAllAsRead" 
                    class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-500/20 transition flex items-center gap-1.5 self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Mark All as Read ({{ $unreadCount }})
            </button>
        @endif
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-300 text-xs font-semibold flex items-center justify-between">
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('message') }}
            </span>
        </div>
    @endif

    <!-- Controls & Filters Bar -->
    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-4 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
            @foreach(['all' => 'All Notifications', 'unread' => 'Unread Only', 'read' => 'Read'] as $fKey => $fLabel)
                <button type="button" wire:click="$set('filter', '{{ $fKey }}')" 
                        class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ $filter === $fKey ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                    {{ $fLabel }}
                </button>
            @endforeach
        </div>

        <div class="relative w-full sm:w-72">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search notification or ticket #..."
                   class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/30" />
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white/80 dark:bg-slate-900/60 border border-slate-200/60 dark:border-slate-800/60 rounded-2xl shadow-sm overflow-hidden divide-y divide-slate-100 dark:divide-slate-800/60">
        @forelse($notifications as $notif)
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
            <div class="p-4 sm:p-5 flex items-start justify-between gap-4 hover:bg-indigo-50/40 dark:hover:bg-indigo-950/20 transition {{ !$notif->is_read ? 'bg-indigo-50/20 dark:bg-indigo-950/15' : '' }}">
                <div class="flex items-start gap-4 min-w-0 flex-1">
                    @if($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $notif->sender_name }}" class="w-10 h-10 rounded-2xl object-cover shrink-0 shadow-sm border border-slate-200 dark:border-slate-700 mt-0.5" onerror="this.outerHTML=`<div class='w-10 h-10 rounded-2xl {{ $badgeColor }} flex items-center justify-center text-sm font-black shrink-0 shadow-sm mt-0.5'>{{ $initials }}</div>`" />
                    @else
                        <div class="w-10 h-10 rounded-2xl {{ $badgeColor }} flex items-center justify-center text-sm font-black shrink-0 shadow-sm mt-0.5">
                            {{ $initials }}
                        </div>
                    @endif

                    <div class="min-w-0 flex-1 space-y-1">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-xs font-black text-slate-900 dark:text-white">
                                {{ $notif->title }}
                            </span>
                            @if(!$notif->is_read)
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-pink-500 text-white animate-pulse">UNREAD</span>
                            @endif
                        </div>

                        <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed">
                            {{ $notif->message }}
                        </p>

                        <div class="flex items-center gap-3 text-[11px] text-slate-400 pt-1">
                            <span class="font-bold text-indigo-600 dark:text-indigo-400">
                                {{ $notif->sender_name }} ({{ ucfirst($notif->sender_type) }})
                            </span>
                            <span>•</span>
                            <span>{{ $notif->created_at->format('M d, Y \a\t g:i a') }} ({{ $notif->created_at->diffForHumans() }})</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" wire:click="markAsRead({{ $notif->id }})" 
                            class="px-3 py-1.5 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-600 hover:text-white text-indigo-600 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/60 text-xs font-bold transition flex items-center gap-1.5"
                            title="Open ticket and mark read">
                        View Ticket
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>

                    <button type="button" wire:click="deleteNotification({{ $notif->id }})" 
                            class="p-1.5 rounded-xl hover:bg-red-100 dark:hover:bg-red-950/50 text-slate-400 hover:text-red-600 dark:hover:text-red-400 transition"
                            title="Delete notification">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <svg class="w-12 h-12 text-slate-300 dark:text-slate-700 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300">No notifications found</h4>
                <p class="text-xs text-slate-400 mt-1">When new replies or ticket alerts arrive, they will appear here.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="pt-2">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
