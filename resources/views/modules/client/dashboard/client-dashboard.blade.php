
<div>
       <x-admin.breadcrumbs
        :items="[
            'Dashboard' => route('client.dashboard'),
        ]"
    />
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
        <div>
            <div class="text-[11px] font-extrabold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1">Executive Overview</div>
            <h1 id="greeting" class="text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                Good Morning, {{ explode(' ', $clientName)[0] }}.
            </h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400">
                Here's how <span class="font-bold text-slate-700 dark:text-slate-200">{{ $clientCompanyName }}</span> is performing this month. Everything is quietly on track.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('client.marketing') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-800/60 transition text-slate-800 dark:text-slate-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                Marketing Report
            </a>
            <a href="{{ route('client.maintenance') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-800/60 transition text-slate-800 dark:text-slate-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/></svg>
                Maintenance Report
            </a>
            <a href="{{ route('client.support') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold shadow-md shadow-indigo-500/20 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-6l-4 4v-4z"/></svg>
                Open Support Ticket
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">

        @foreach($statCards as $card)

            <a href="{{ $card['url'] }}"
            class="block bg-white dark:bg-slate-900/60 rounded-2xl border border-slate-100 dark:border-slate-800/60 p-5 hover:border-slate-300 dark:hover:border-slate-700 hover:shadow transition">

                <div class="flex items-center justify-between mb-3">

                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 dark:text-slate-500">
                        {{ $card['label'] }}
                    </span>

                    <svg class="w-5 h-5 text-slate-300 dark:text-slate-500"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="{{ $card['icon'] }}" />

                    </svg>

                </div>

                <div class="text-3xl font-extrabold text-slate-900 dark:text-white">

                    {{ $card['value'] }}

                </div>

                <div class="text-xs text-slate-400 dark:text-slate-500 mt-2">

                    {{ $card['sub'] }}

                </div>

            </a>

        @endforeach

    </div>

    <script>
        const hour = new Date().getHours();

        let greeting = 'Good Evening';

        if (hour < 12) {
            greeting = 'Good Morning';
        } else if (hour < 18) {
            greeting = 'Good Afternoon';
        }

        document.getElementById('greeting').innerHTML =
            `${greeting}, {{ explode(' ', $clientName)[0] }}.`;
    </script>