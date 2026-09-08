<div x-data="{ showCreateModal: @entangle('showCreateModal') }" @keydown.escape.window="$wire.closeTicket()" class="space-y-6">
    <!-- Top Bar Header -->
    <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-5 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Support Center
            </h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                Raise support tickets for your websites and chat directly with your assigned account managers.
            </p>
        </div>

        <button type="button" @click="showCreateModal = true" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-500/20 transition shrink-0">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Raise New Ticket
        </button>
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

    <!-- Main Support Center Layout (2 Columns) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 min-h-[600px]">
        <!-- Left Sidebar: Ticket List & Search (4 Columns) -->
        <div class="lg:col-span-4 space-y-4" x-data="{ localTab: 'all' }">
            <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-4 shadow-sm space-y-3">
                <!-- Search Box -->
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search ticket # or subject..."
                           class="w-full pl-9 pr-4 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/30" />
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <!-- Status Filter Pills -->
                <div class="flex items-center gap-1 overflow-x-auto no-scrollbar pb-1">
                    @foreach(['all' => 'All', 'open' => 'Open', 'in_progress' => 'In Progress', 'resolved' => 'Resolved', 'closed' => 'Closed'] as $statusKey => $statusLabel)
                        <button type="button" @click="localTab = '{{ $statusKey }}'"
                                :class="localTab === '{{ $statusKey }}' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700'"
                                class="px-2.5 py-1 rounded-lg text-[11px] font-bold whitespace-nowrap transition">
                            {{ $statusLabel }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Tickets List -->
            <div class="space-y-2.5 max-h-[580px] overflow-y-auto pr-1">
                @forelse($tickets as $t)
                    <div wire:click="selectTicket({{ $t->id }})"
                         x-show="localTab === 'all' || localTab === '{{ $t->status }}'"
                         class="p-4 rounded-2xl border transition-all cursor-pointer {{ $selectedTicketId === $t->id ? 'bg-indigo-50/80 dark:bg-indigo-950/40 border-indigo-500/50 shadow-md ring-1 ring-indigo-500/30' : 'bg-white/70 dark:bg-slate-900/50 border-slate-200/60 dark:border-slate-800/60 hover:border-slate-300 dark:hover:border-slate-700' }}">
                        <div class="flex items-start justify-between gap-2 mb-1.5">
                            <div class="flex items-center gap-1.5">
                                <span class="text-[11px] font-black text-indigo-600 dark:text-indigo-400 tracking-wider">
                                    {{ $t->ticket_number }}
                                </span>
                                @if(($t->unread_count ?? 0) > 0)
                                    <span class="px-1.5 py-0.5 rounded-full text-[9px] font-black bg-emerald-500 text-white shadow-sm animate-pulse" title="{{ $t->unread_count }} unread messages">
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
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase border {{ $statusClasses[$t->status] ?? 'bg-slate-100 text-slate-600' }}">
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
                            <span class="truncate font-semibold text-slate-600 dark:text-slate-300">
                                🌐 {{ $t->website->site_name ?? 'Website' }}
                            </span>
                            <span>{{ $t->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center bg-white/40 dark:bg-slate-900/30 rounded-2xl border border-dashed border-slate-200 dark:border-slate-800">
                        <svg class="w-8 h-8 text-slate-300 dark:text-slate-600 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">No support tickets found.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Column: Ticket Conversation & Chat (8 Columns) -->
        <div class="lg:col-span-8 flex flex-col">
            @if ($selectedTicket)
                <div class="bg-white/80 dark:bg-slate-900/60 border border-slate-200/60 dark:border-slate-800/60 rounded-2xl shadow-sm flex flex-col h-full overflow-hidden">
                    <!-- Ticket Header -->
                    <div class="p-4 sm:p-5 border-b border-slate-200/60 dark:border-slate-800/60 bg-slate-50/50 dark:bg-slate-900/40">
                        <div class="flex items-start justify-between gap-3 pb-3">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-black text-indigo-600 dark:text-indigo-400">{{ $selectedTicket->ticket_number }}</span>
                                    <span class="text-xs font-bold text-slate-400">•</span>
                                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">🌐 {{ $selectedTicket->website->site_name ?? 'Website' }}</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                                        {{ ucfirst($selectedTicket->category) }}
                                    </span>
                                </div>
                                <h3 class="text-base font-extrabold text-slate-900 dark:text-white">
                                    {{ $selectedTicket->subject }}
                                </h3>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase border {{ $statusClasses[$selectedTicket->status] ?? 'bg-slate-100' }}">
                                    {{ str_replace('_', ' ', $selectedTicket->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Body Wrapper with Fixed Watermark Overlay -->
                    <div class="relative flex-1 min-h-0 bg-slate-50/30 dark:bg-slate-950/20">
                        <!-- Fixed Centered Logo Watermark (Stays in center of chat viewport at all times) -->
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.05] dark:opacity-[0.09] select-none z-0">
                            <img src="{{ asset('aspire-hub-1.svg') }}" class="w-48 sm:w-64 max-w-[50%] h-auto filter grayscale" alt="Watermark" />
                        </div>

                        <!-- Chat Conversation Thread (Smooth auto-scroll) -->
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
                                    $isMe = $msg->sender_type === 'client' && $msg->sender_id === auth()->id();
                                    $senderAvatarUrl = $msg->sender?->getProfileImageUrl();
                                    $senderInitials = $msg->sender?->getInitials() ?? strtoupper(substr($msg->sender->name ?? 'U', 0, 2));
                                @endphp
                                <div class="flex items-start gap-3 {{ $isMe ? 'flex-row-reverse' : '' }}">
                                    @if($senderAvatarUrl)
                                        <img src="{{ $senderAvatarUrl }}" alt="{{ $msg->sender->name ?? 'User' }}"
                                             class="w-8 h-8 rounded-full object-cover shrink-0 shadow-sm border border-slate-200 dark:border-slate-700"
                                             onerror="this.outerHTML=`<div class='w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0 shadow-sm {{ $isMe ? 'bg-indigo-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200' }}'>{{ $senderInitials }}</div>`" />
                                    @else
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0 shadow-sm {{ $isMe ? 'bg-indigo-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200' }}">
                                            {{ $senderInitials }}
                                        </div>
                                    @endif
                                    <div class="max-w-[75%] space-y-1">
                                        <div class="flex items-center gap-2 {{ $isMe ? 'flex-row-reverse' : '' }}">
                                            <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300">
                                                {{ $isMe ? 'You' : ($msg->sender->name ?? 'Support Staff') }}
                                            </span>
                                            <span class="text-[9px] text-slate-400">
                                                {{ $msg->created_at->format('M d, g:i a') }}
                                            </span>
                                        </div>
                                        <div class="p-3.5 rounded-2xl text-xs leading-relaxed shadow-sm {{ $isMe ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 border border-slate-200/60 dark:border-slate-700/60 rounded-tl-none' }}">
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

                    <!-- Message Reply Form -->
                    @if($selectedTicket->status === 'closed')
                        <div class="p-4 bg-slate-100 dark:bg-slate-800 text-center text-xs font-semibold text-slate-500 dark:text-slate-400 border-t border-slate-200 dark:border-slate-700">
                            This ticket has been marked as closed.
                        </div>
                    @else
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
                             @paste="handlePaste($event)"
                             @dragover.prevent
                             @drop.prevent="handleDrop($event)"
                             class="border-t border-slate-200/60 dark:border-slate-800/60 bg-white dark:bg-slate-900 p-3 sm:p-4 space-y-2">

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
                                <button type="submit" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition shrink-0 flex items-center gap-1.5">
                                    <span>Send</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-white/60 dark:bg-slate-900/40 border border-slate-200/50 dark:border-slate-800/40 rounded-2xl p-12 text-center h-full flex flex-col items-center justify-center">
                    <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-1">Select a Ticket</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Click on any ticket from the left list to view conversation history and reply.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Create Ticket Modal (Mandatory Website Selection) -->
    <div x-data="{
            showCreateModal: @entangle('showCreateModal'),
            handleModalPaste(e) {
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
                    const fileInput = $refs.createFileInput;
                    const dataTransfer = new DataTransfer();
                    if (fileInput.files) {
                        Array.from(fileInput.files).forEach(f => dataTransfer.items.add(f));
                    }
                    files.forEach(f => dataTransfer.items.add(f));
                    fileInput.files = dataTransfer.files;
                    fileInput.dispatchEvent(new Event('change'));
                }
            }
        }"
        x-show="showCreateModal"
        x-cloak
        style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm animate-fadeIn p-4">
        <div @click.outside="showCreateModal = false" class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden" @paste="handleModalPaste">
            
            <div class="p-5 overflow-y-auto max-h-[85vh]">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Raise New Support Ticket
                    </h3>
                    <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-lg font-bold">✕</button>
                </div>

                <form wire:submit.prevent="createTicket" class="space-y-4">
                    <!-- Website Selection (MANDATORY) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Select Website <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="website_id" class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/40">
                            <option value="">-- Choose your registered website --</option>
                            @foreach($websites as $web)
                                <option value="{{ $web->id }}">{{ $web->site_name }} ({{ parse_url($web->url, PHP_URL_HOST) ?? $web->url }})</option>
                            @endforeach
                        </select>
                        @error('website_id') <span class="text-[10px] text-red-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Ticket Subject <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="subject" placeholder="e.g. Website Loading Issue or Content Update Request" class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/40">
                        @error('subject') <span class="text-[10px] text-red-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Category</label>
                            <select wire:model="category" class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/40">
                                <option value="general">General Inquiry</option>
                                <option value="bug">Bug Report / Error</option>
                                <option value="feature">Feature Request</option>
                                <option value="content">Content Update</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Priority</label>
                            <select wire:model="priority" class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500/40">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Description / Details <span class="text-red-500">*</span>
                        </label>
                        <textarea wire:model="message" rows="4" placeholder="Describe your issue or request in detail (or paste image Ctrl+V)..." class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500/40 resize-none"></textarea>
                        @error('message') <span class="text-[10px] text-red-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1 flex items-center gap-1">
                            Attach Files <span class="text-slate-400 font-normal">(Image, PDF, Excel, Video - Paste Ctrl+V enabled)</span>
                        </label>
                        <input type="file" wire:model="createAttachments" multiple x-ref="createFileInput" class="block w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-400" />
                        <div wire:loading wire:target="createAttachments" class="text-xs text-indigo-600 dark:text-indigo-400 mt-1 italic font-semibold">Uploading attachments...</div>
                        @error('createAttachments.*') <span class="text-[10px] text-red-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    @if($createAttachments)
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach($createAttachments as $index => $file)
                                <div class="inline-flex items-center gap-1.5 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/50 rounded-lg px-2 py-1 relative group">
                                    <span class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 truncate max-w-[120px]">{{ $file->getClientOriginalName() }}</span>
                                    <button type="button" wire:click="removeCreateAttachment({{ $index }})" class="text-indigo-400 hover:text-red-500 transition-colors">
                                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                    <!-- Remove Overlay Loader -->
                                    <div wire:loading wire:target="removeCreateAttachment({{ $index }})" class="absolute inset-0 bg-indigo-50/80 dark:bg-slate-900/80 rounded-lg flex items-center justify-center">
                                        <svg class="w-3 h-3 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-3 border-t border-slate-200 dark:border-slate-800 pt-3">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-500/20">
                            Submit Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
