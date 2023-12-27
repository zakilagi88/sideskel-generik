<div class="flex flex-col justify-start w-1/5 space-y-2">
    @foreach ($this->statistik as $stat)
        <a class="px-4 py-2 text-sm transition-all duration-300 ease-in-out text-gray-600
            block bg-primary-300 rounded-lg shadow-md mb-1"
            :class="{
                'z-20 border-l-2 transform translate-x-2 border-blue-500 font-bold': tab === {{ $stat->id }},
                'hover:bg-gray-300': tab !== {{ $stat->id }},
                ' transform -translate-x-2': tab !== {{ $stat->id }}
            }"
            href="#" @click.prevent="tab = {{ $stat->id }}">
            {{ $stat->judul }}
        </a>
    @endforeach
</div>
