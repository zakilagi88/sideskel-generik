<x-filament-widgets::widget>
    <div class="w-full flex flex-wrap md:justify-center" data-aos="fade-in">

        @foreach ($stats as $stat)
            <x-custom.stat :stat="$stat" />
        @endforeach

    </div>

</x-filament-widgets::widget>
