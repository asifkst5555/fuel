@props([
    'headers' => [],
])

<div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-slate-200']) }}>
            @if ($headers)
                <thead class="bg-slate-50">
                    <tr>
                        @foreach ($headers as $header)
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500">
                                {{ $header }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
            @endif

            <tbody class="divide-y divide-slate-200 bg-white">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
