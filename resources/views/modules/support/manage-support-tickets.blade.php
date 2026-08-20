<div @keydown.escape.window="$wire.closeTicket()" class="space-y-6">
    <!-- Breadcrumbs & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <x-admin.breadcrumbs :items="['Support Center' => null]" />
            <h1 class="text-2xl font-black text-slate-900 dark:text-white mt-1">Support Center</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Oversee all client tickets, assign staff members, and reply to support inquiries.
            </p>
        </div>
    </div>

    <!-- Flash Notification -->
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

    <!-- Main Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 min-h-[620px]">
        <!-- Left Sidebar: Ticket Filter & List (4 Columns) -->
        <div class="lg:col-span-4 space-y-4">
            <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-4 shadow-sm space-y-3">
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search ticket #, subject or client..."
                           class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/30" />
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <!-- Filters Grid (Searchable Combobox Dropdowns) -->
                <div class="grid grid-cols-2 gap-2">
                    <!-- Searchable Client Filter Dropdown -->
                    <div x-data="{
                            open: false,
                            search: '',
                            get selectedName() {
                                let id = $wire.clientFilter;
                                if (!id) return 'All Clients';
                                @foreach($allClients as $c)
                                    if (id == {{ $c->id }}) return '{{ e($c->display_name) }}';
                                @endforeach
                                return 'All Clients';
                            },
                            selectClient(id) {
                                $wire.set('clientFilter', id);
                                this.open = false;
                                this.search = '';
                            }
                         }"
                         @click.away="open = false"
                         class="relative">

                        <button type="button"
                                @click="open = !open; if(open) $nextTick(() => $refs.searchInput.focus())"
                                class="w-full px-2.5 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-[11px] font-semibold text-slate-700 dark:text-slate-300 flex items-center justify-between gap-1 shadow-sm hover:border-indigo-500 transition-colors">
                            <span class="truncate" x-text="selectedName">All Clients</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute left-0 right-0 mt-1 z-50 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl p-1.5 space-y-1 max-h-64 overflow-hidden flex flex-col"
                             style="display: none;">

                            <div class="relative p-1">
                                <input type="text"
                                       x-model="search"
                                       x-ref="searchInput"
                                       @click.stop
                                       placeholder="🔍 Search client..."
                                       class="w-full px-2.5 py-1.5 text-[11px] rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 font-medium focus:outline-none focus:ring-1 focus:ring-indigo-500" />
                            </div>

                            <div class="overflow-y-auto max-h-48 space-y-0.5 custom-scrollbar">
                                <button type="button"
                                        @click="selectClient('')"
                                        class="w-full text-left px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition-colors flex items-center justify-between"
                                        :class="!$wire.clientFilter ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 font-extrabold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-[9px] flex items-center justify-center shrink-0">👥</div>
                                        <span>All Clients</span>
                                    </div>
                                    <span x-show="!$wire.clientFilter" class="text-indigo-600 font-bold">✓</span>
                                </button>

                                @foreach($allClients as $c)
                                    @php
                                        $cAvatar = $c->user?->getProfileImageUrl();
                                        $cInitials = $c->getInitials();
                                    @endphp
                                    <button type="button"
                                            x-show="!search || '{{ strtolower(e($c->display_name)) }}'.includes(search.toLowerCase())"
                                            @click="selectClient({{ $c->id }})"
                                            class="w-full text-left px-2 py-1.5 rounded-lg text-[11px] font-semibold transition-colors flex items-center justify-between gap-2"
                                            :class="$wire.clientFilter == {{ $c->id }} ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 font-extrabold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                        <div class="flex items-center gap-2 truncate">
                                            @if($cAvatar)
                                                <img src="{{ $cAvatar }}" alt="{{ $c->user->name ?? 'Client' }}" class="w-5 h-5 rounded-full object-cover shrink-0 shadow-sm border border-slate-200 dark:border-slate-700" />
                                            @else
                                                <div class="w-5 h-5 rounded-full bg-emerald-600 text-white font-bold text-[8px] flex items-center justify-center shrink-0 shadow-sm">
                                                    {{ $cInitials }}
                                                </div>
                                            @endif
                                            <span class="truncate">{{ $c->display_name }}</span>
                                        </div>
                                        <span x-show="$wire.clientFilter == {{ $c->id }}" class="text-indigo-600 font-bold shrink-0">✓</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Searchable Staff Filter Dropdown -->
                    <div x-data="{
                            open: false,
                            search: '',
                            get selectedName() {
                                let id = $wire.staffFilter;
                                if (!id) return 'All Staff';
                                @foreach($allStaff as $s)
                                    if (id == {{ $s->id }}) return '{{ e($s->user->name ?? 'Staff') }}';
                                @endforeach
                                return 'All Staff';
                            },
                            selectStaff(id) {
                                $wire.set('staffFilter', id);
                                this.open = false;
                                this.search = '';
                            }
                         }"
                         @click.away="open = false"
                         class="relative">

                        <button type="button"
                                @click="open = !open; if(open) $nextTick(() => $refs.staffSearchInput.focus())"
                                class="w-full px-2.5 py-1.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-[11px] font-semibold text-slate-700 dark:text-slate-300 flex items-center justify-between gap-1 shadow-sm hover:border-indigo-500 transition-colors">
                            <span class="truncate" x-text="selectedName">All Staff</span>
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute left-0 right-0 mt-1 z-50 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-xl p-1.5 space-y-1 max-h-64 overflow-hidden flex flex-col"
                             style="display: none;">

                            <div class="relative p-1">
                                <input type="text"
                                       x-model="search"
                                       x-ref="staffSearchInput"
                                       @click.stop
                                       placeholder="🔍 Search staff..."
                                       class="w-full px-2.5 py-1.5 text-[11px] rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 font-medium focus:outline-none focus:ring-1 focus:ring-indigo-500" />
                            </div>

                            <div class="overflow-y-auto max-h-48 space-y-0.5 custom-scrollbar">
                                <button type="button"
                                        @click="selectStaff('')"
                                        class="w-full text-left px-2.5 py-1.5 rounded-lg text-[11px] font-semibold transition-colors flex items-center justify-between"
                                        :class="!$wire.staffFilter ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 font-extrabold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                    <div class="flex items-center gap-2">
                                        <div class="w-5 h-5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300 font-bold text-[9px] flex items-center justify-center shrink-0">👔</div>
                                        <span>All Staff</span>
                                    </div>
                                    <span x-show="!$wire.staffFilter" class="text-indigo-600 font-bold">✓</span>
                                </button>

                                @foreach($allStaff as $s)
                                    @php
                                        $sAvatar = $s->user?->getProfileImageUrl();
                                        $sInitials = $s->user?->getInitials() ?? strtoupper(substr($s->user->name ?? 'S', 0, 2));
                                    @endphp
                                    <button type="button"
                                            x-show="!search || '{{ strtolower(e($s->user->name ?? 'Staff')) }}'.includes(search.toLowerCase())"
                                            @click="selectStaff({{ $s->id }})"
                                            class="w-full text-left px-2 py-1.5 rounded-lg text-[11px] font-semibold transition-colors flex items-center justify-between gap-2"
                                            :class="$wire.staffFilter == {{ $s->id }} ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 font-extrabold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800'">
                                        <div class="flex items-center gap-2 truncate">
                                            @if($sAvatar)
                                                <img src="{{ $sAvatar }}" alt="{{ $s->user->name ?? 'Staff' }}" class="w-5 h-5 rounded-full object-cover shrink-0 shadow-sm border border-slate-200 dark:border-slate-700" />
                                            @else
                                                <div class="w-5 h-5 rounded-full bg-blue-600 text-white font-bold text-[8px] flex items-center justify-center shrink-0 shadow-sm">
                                                    {{ $sInitials }}
                                                </div>
                                            @endif
                                            <span class="truncate">{{ $s->user->name ?? 'Staff' }}</span>
                                        </div>
                                        <span x-show="$wire.staffFilter == {{ $s->id }}" class="text-indigo-600 font-bold shrink-0">✓</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Filter Pills -->
                <div class="flex items-center gap-1 overflow-x-auto no-scrollbar pb-1">
                    @foreach(['all' => 'All', 'open' => 'Open', 'in_progress' => 'In Progress', 'resolved' => 'Resolved', 'closed' => 'Closed'] as $statusKey => $statusLabel)
                        <button type="button" wire:click="$set('activeTab', '{{ $statusKey }}')"
                                class="px-2.5 py-1 rounded-lg text-[11px] font-bold whitespace-nowrap transition {{ $activeTab === $statusKey ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                            {{ $statusLabel }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Tickets List -->
            <div class="space-y-2.5 max-h-[580px] overflow-y-auto pr-1">
                @forelse($tickets as $t)
                    <div wire:click="selectTicket({{ $t->id }})"
                         class="p-4 rounded-2xl border transition-all cursor-pointer {{ $selectedTicketId === $t->id ? 'bg-indigo-50/80 dark:bg-indigo-950/40 border-indigo-500/50 shadow-md ring-1 ring-indigo-500/30' : 'bg-white/70 dark:bg-slate-900/50 border-slate-200/60 dark:border-slate-800/60 hover:border-slate-300 dark:hover:border-slate-700' }}">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <div class="flex items-center gap-1.5">
                                <span class="text-[11px] font-black text-indigo-600 dark:text-indigo-400 tracking-wider">
                                    {{ $t->ticket_number }}
                                </span>
                                @if(($t->unread_count ?? 0) > 0)
                                    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black bg-indigo-600 text-white shadow-sm animate-pulse" title="{{ $t->unread_count }} unread messages">
                                        {{ $t->unread_count > 9 ? '9+' : $t->unread_count }}
                                    </span>
                                @endif
                            </div>
                            @php
                                $statusClasses = [
                                    'open' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20',
                                    'in_progress' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20',
                                    'resolved' => 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-500/20',
                                    'closed' => 'bg-slate-500/10 text-slate-600 dark:text-slate-400 border-slate-500/20',
                                ];
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase border {{ $statusClasses[$t->status] ?? 'bg-slate-100' }}">
                                {{ str_replace('_', ' ', $t->status) }}
                            </span>
                        </div>

                        <h4 class="text-xs font-bold text-slate-900 dark:text-white line-clamp-1 mb-1">
                            {{ $t->subject }}
                        </h4>

                        @if(!empty($drafts[$t->id]) || !empty($draftAttachments[$t->id]))
                            <div class="mt-1.5 flex items-center gap-1.5 text-[10px] font-bold text-amber-600 dark:text-amber-400 bg-amber-500/10 dark:bg-amber-500/20 px-2 py-1 rounded-lg border border-amber-500/20">
                                <span class="font-extrabold uppercase tracking-wider text-[8px] px-1 py-0.2 bg-amber-500 text-white rounded">Draft</span>
                                <span class="truncate italic font-medium text-slate-700 dark:text-slate-300">
                                    @if(!empty($drafts[$t->id]))
                                        {{ Str::limit($drafts[$t->id], 20) }}
                                    @endif
                                    @if(!empty($draftAttachments[$t->id]))
                                        <span class="text-amber-600 dark:text-amber-400 font-extrabold ml-1">📎 {{ count($draftAttachments[$t->id]) }} {{ Str::plural('file', count($draftAttachments[$t->id])) }}</span>
                                    @endif
                                </span>
                            </div>
                        @endif

                        <div class="flex items-center justify-between text-[10px] text-slate-500 dark:text-slate-400 mt-2">
                            <span class="truncate font-bold text-slate-700 dark:text-slate-300">
                                👤 {{ $t->client->display_name }}
                            </span>
                            <span>{{ $t->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center bg-white/40 dark:bg-slate-900/30 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800">
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">No support tickets found.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Column: Ticket Conversation, Staff Assignment & Controls (8 Columns) -->
        <div class="lg:col-span-8 flex flex-col">
            @if ($selectedTicket)
                <div class="bg-white/80 dark:bg-slate-900/60 border border-slate-200/60 dark:border-slate-800/60 rounded-2xl shadow-sm flex flex-col h-full overflow-hidden">
                    <!-- Header with Controls -->
                    <div class="p-4 sm:p-5 border-b border-slate-200/60 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-900/40 space-y-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="text-xs font-black text-indigo-600 dark:text-indigo-400">{{ $selectedTicket->ticket_number }}</span>
                                    <span class="text-xs font-bold text-slate-400">•</span>
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-200">👤 {{ $selectedTicket->client->display_name }}</span>
                                    <span class="text-xs font-semibold text-slate-500">🌐 {{ $selectedTicket->website->site_name ?? 'Website' }}</span>
                                </div>
                                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">
                                    {{ $selectedTicket->subject }}
                                </h3>
                            </div>
                        </div>

                        <!-- Status & Assign Staff Bar -->
                        <div class="flex flex-wrap items-center gap-3 pt-2 border-t border-slate-200/40 dark:border-slate-800/40">
                            <!-- Status Selector -->
                            <div class="flex items-center gap-1.5">
                                <span class="text-[11px] font-bold text-slate-500">Status:</span>
                                <select wire:change="updateStatus($event.target.value)" class="px-2.5 py-1 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/40">
                                    <option value="open" {{ $selectedTicket->status === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="in_progress" {{ $selectedTicket->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="resolved" {{ $selectedTicket->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="closed" {{ $selectedTicket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>

                            <!-- Assign Staff Selector -->
                            <div class="flex items-center gap-1.5">
                                <span class="text-[11px] font-bold text-slate-500">Assign Staff:</span>
                                <select wire:change="assignStaff($event.target.value)" class="px-2.5 py-1 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/40">
                                    <option value="">-- Unassigned --</option>
                                    @foreach($allStaff as $st)
                                        <option value="{{ $st->id }}" {{ $selectedTicket->assigned_staff_id === $st->id ? 'selected' : '' }}>
                                            {{ $st->user->name ?? 'Staff' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Body Wrapper with Fixed Watermark Overlay -->
                    <div class="relative flex-1 min-h-0 bg-slate-50/30 dark:bg-slate-950/20 flex flex-col">
                        <!-- Fixed Centered Logo Watermark (Stays in center of chat viewport at all times) -->
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.05] dark:opacity-[0.09] select-none z-0">
                            <img src="{{ asset('aspire-hub-1.svg') }}" class="w-48 sm:w-64 max-w-[50%] h-auto filter grayscale" alt="Watermark" />
                        </div>

                        <!-- Chat Thread (Smooth auto-scroll) -->
                        <div x-data="{
                                scrollToBottom() {
                                    $nextTick(() => {
                                        this.$el.scrollTop = this.$el.scrollHeight;
                                    });
                                }
                             }"
                             x-init="scrollToBottom()"
                             x-effect="scrollToBottom()"
                             class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4 max-h-[520px] min-h-[350px] relative z-10 custom-scrollbar">
                            @foreach ($selectedTicket->messages as $msg)
                                @php
                                    $isAdmin = $msg->sender_type === 'admin';
                                    $isMe = $msg->sender_id === auth()->id();
                                    $senderAvatarUrl = $msg->sender?->getProfileImageUrl();
                                    $senderInitials = $msg->sender?->getInitials() ?? strtoupper(substr($msg->sender->name ?? 'U', 0, 2));
                                @endphp
                                <div class="flex items-start gap-3 {{ $isAdmin || $isMe ? 'flex-row-reverse' : '' }}">
                                    @if($senderAvatarUrl)
                                        <img src="{{ $senderAvatarUrl }}" alt="{{ $msg->sender->name ?? 'User' }}"
                                             class="w-8 h-8 rounded-full object-cover shrink-0 shadow-sm border border-slate-200 dark:border-slate-700"
                                             onerror="this.outerHTML=`<div class='w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0 shadow-sm {{ $isAdmin ? 'bg-indigo-600 text-white' : ($msg->sender_type === 'staff' ? 'bg-blue-600 text-white' : 'bg-indigo-600 text-white') }}'>{{ $senderInitials }}</div>`" />
                                    @else
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0 shadow-sm {{ $isAdmin ? 'bg-indigo-600 text-white' : ($msg->sender_type === 'staff' ? 'bg-blue-600 text-white' : 'bg-indigo-600 text-white') }}">
                                            {{ $senderInitials }}
                                        </div>
                                    @endif
                                    <div class="max-w-[75%] space-y-1">
                                        <div class="flex items-center gap-2 {{ $isAdmin || $isMe ? 'flex-row-reverse' : '' }}">
                                            <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300">
                                                {{ $msg->sender->name ?? ucfirst($msg->sender_type) }}
                                                <span class="text-[9px] font-normal text-slate-400">({{ ucfirst($msg->sender_type) }})</span>
                                            </span>
                                            <span class="text-[9px] text-slate-400">
                                                {{ $msg->created_at->format('M d, g:i a') }}
                                            </span>
                                        </div>
                                        <div class="p-3.5 rounded-2xl text-xs leading-relaxed shadow-sm {{ $isAdmin ? 'bg-indigo-600 text-white rounded-tr-none shadow-indigo-500/10' : ($msg->sender_type === 'staff' ? 'bg-blue-600 text-white rounded-tr-none' : 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 border border-slate-200/60 dark:border-slate-700/60 rounded-tl-none') }}">
                                            {!! nl2br(e($msg->message)) !!}

                                            <!-- Attachments Preview -->
                                            @if(!empty($msg->attachments))
                                                <div class="mt-2.5 space-y-2">
                                                    @foreach($msg->attachments as $att)
                                                        @php
                                                            $mime = strtolower($att['type'] ?? '');
                                                            $name = $att['name'] ?? 'Attachment';
                                                            $url = $att['url'] ?? asset('storage/' . ($att['path'] ?? ''));
                                                            $isImg = Str::contains($mime, ['image', 'png', 'jpg', 'jpeg', 'webp', 'gif']);
                                                            $isVideo = Str::contains($mime, ['video', 'mp4', 'mov', 'avi', 'mkv', 'webm']);
                                                        @endphp

                                                        @if($isImg)
                                                            <a href="{{ $url }}" target="_blank" class="block group overflow-hidden rounded-xl border border-slate-200/50 dark:border-slate-700/50 max-w-xs mt-1">
                                                                <img src="{{ $url }}" alt="{{ $name }}" class="max-h-48 w-auto object-cover group-hover:scale-105 transition-transform" />
                                                            </a>
                                                        @elseif($isVideo)
                                                            <div class="rounded-xl overflow-hidden max-w-xs border border-slate-200/50 dark:border-slate-700/50 bg-black mt-1">
                                                                <video controls class="max-h-48 w-full">
                                                                    <source src="{{ $url }}" type="{{ $mime }}">
                                                                    Your browser does not support video.
                                                                </video>
                                                            </div>
                                                        @else
                                                            <a href="{{ $url }}" target="_blank" download class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-900/60 text-slate-800 dark:text-slate-100 text-xs font-bold border border-slate-200 dark:border-slate-700 hover:bg-indigo-500 hover:text-white transition-colors mt-1">
                                                                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                </svg>
                                                                <span class="truncate max-w-[180px]">{{ $name }}</span>
                                                                <span class="text-[9px] font-normal opacity-75">({{ Str::upper(pathinfo($name, PATHINFO_EXTENSION)) }})</span>
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Admin Reply Form -->
                    <div x-data="{
                            handlePaste(e) {
                                const items = (e.clipboardData || window.clipboardData).items;
                                const files = [];
                                if (items) {
                                    for (let i = 0; i < items.length; i++) {
                                        if (items[i].kind === 'file') {
                                            const file = items[i].getAsFile();
                                            if (file) files.push(file);
                                        }
                                    }
                                }
                                if (files.length > 0) {
                                    const fileInput = $refs.replyFileInput;
                                    const dataTransfer = new DataTransfer();
                                    if (fileInput.files) {
                                        Array.from(fileInput.files).forEach(f => dataTransfer.items.add(f));
                                    }
                                    files.forEach(f => dataTransfer.items.add(f));
                                    fileInput.files = dataTransfer.files;
                                    fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                                }
                            },
                            handleDrop(e) {
                                const files = Array.from(e.dataTransfer?.files || []);
                                if (files.length > 0) {
                                    const fileInput = $refs.replyFileInput;
                                    const dataTransfer = new DataTransfer();
                                    if (fileInput.files) {
                                        Array.from(fileInput.files).forEach(f => dataTransfer.items.add(f));
                                    }
                                    files.forEach(f => dataTransfer.items.add(f));
                                    fileInput.files = dataTransfer.files;
                                    fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                                }
                            }
                         }"
                         @paste.window="handlePaste($event)"
                         @dragover.prevent
                         @drop.prevent="handleDrop($event)"
                         class="p-4 border-t border-slate-200/60 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-900/40 space-y-3">

                        @if (!empty($replyAttachments))
                            <div class="flex flex-wrap gap-2 px-1">
                                @foreach ($replyAttachments as $idx => $file)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 text-[10px] font-bold border border-indigo-200 dark:border-indigo-800">
                                        📎 <span class="truncate max-w-[150px]">{{ $file->getClientOriginalName() }}</span>
                                        <button type="button" wire:click="removeReplyAttachment({{ $idx }})" class="hover:text-red-500 font-black">✕</button>
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <form wire:submit.prevent="sendReply" class="flex items-center gap-2">
                            <label class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 cursor-pointer shrink-0 transition" title="Attach Image, PDF, Excel, Video (or paste Ctrl+V)">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                <input type="file" x-ref="replyFileInput" wire:model="replyAttachments" multiple class="hidden" />
                            </label>
                            <input type="text" wire:model="replyMessage" placeholder="Type reply or paste image (Ctrl+V / Cmd+V)..."
                                   class="flex-1 px-4 py-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/40" />
                            <button type="submit" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md shadow-indigo-500/20 transition shrink-0 flex items-center gap-1.5">
                                <span>Reply as Admin</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-12 text-center h-full flex flex-col items-center justify-center">
                    <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Select a Ticket</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Select any ticket from the list to view details and manage conversation.</p>
                </div>
            @endif
        </div>
    </div>
</div>
