@props(['headers' => []])
<div class="overflow-x-auto rounded-2xl border border-slate-200/50 dark:border-slate-800/50">
    <table {{ $attributes->merge(['class' => 'w-full text-left border-collapse bg-white/40 dark:bg-slate-900/10 backdrop-blur-md']) }}>
        <thead>
            <tr class="border-b border-slate-200/50 dark:border-slate-800/50 bg-slate-50/50 dark:bg-slate-950/20 text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                @foreach($headers as $header)
                    <th class="px-6 py-4 font-semibold">{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-900/50 text-sm">
            {{ $slot }}
        </tbody>
    </table>
</div>
