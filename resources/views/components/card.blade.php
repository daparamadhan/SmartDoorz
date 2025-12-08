<div class="bg-white rounded-xl shadow p-5">
    @if(isset($title))
        <h3 class="text-lg font-bold mb-2">{{ $title }}</h3>
    @endif

    <div>
        {{ $slot }}
    </div>
</div>
