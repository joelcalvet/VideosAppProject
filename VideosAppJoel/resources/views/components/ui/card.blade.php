<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow p-6']) }}>
    @if(isset($title) || isset($actions))
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 pb-4 border-b">
            @isset($title)
                <h2 class="text-lg font-semibold text-gray-900">
                    {{ $title }}
                </h2>
            @endisset

            @isset($actions)
                <div class="flex gap-2">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    @endif

    <div class="text-base text-gray-700">
        {{ $slot }}
    </div>
</div>
