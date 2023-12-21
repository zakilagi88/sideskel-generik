<div class="flex flex-col justify-start w-1/4 space-y-4">
    @foreach ($this->statistik as $stat)
        <a class="px-4 py-2 text-sm"
            :class="{
                'z-20 border-l-2 transform translate-x-2 border-blue-500 font-bold': tab ===
                    {{ $stat->id }},
                ' transform -translate-x-2': tab !== {{ $stat->id }}
            }"
            href="#" @click.prevent="tab = {{ $stat->id }}">
            {{ $stat->heading_tabel }}
        </a>
    @endforeach
    {{-- <a class="px-4 py-2 text-sm"
        :class="{
            'z-20 border-l-2 transform translate-x-2 border-blue-500 font-bold': tab ===
                2,
            ' transform -translate-x-2': tab !== 2
        }"
        href="#" @click.prevent="tab = 2">
        KATEGORI UMUR
    </a> --}}
    {{-- <a class="px-4 py-2 text-sm"
        :class="{
            'z-20 border-l-2 transform translate-x-2 border-blue-500 font-bold': tab ===
                3,
            ' transform -translate-x-2': tab !== 3
        }"
        href="#" @click.prevent="tab = 3">
        BATMAN FOREVER (1995)
    </a>
    <a class="px-4 py-2 text-sm"
        :class="{
            'z-20 border-l-2 transform translate-x-2 border-blue-500 font-bold': tab ===
                4,
            ' transform -translate-x-2': tab !== 4
        }"
        href="#" @click.prevent="tab = 4">
        BATMAN: THE KILLING JOKE (2016)
    </a>
    <a class="px-4 py-2 text-sm"
        :class="{
            'z-20 border-l-2 transform translate-x-2 border-blue-500 font-bold': tab ===
                5,
            ' transform -translate-x-2': tab !== 5
        }"
        href="#" @click.prevent="tab = 5">
        JUSTICE LEAGUE (2017)
    </a> --}}
</div>
