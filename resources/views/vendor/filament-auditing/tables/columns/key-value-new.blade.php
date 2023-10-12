<div class="my-2 text-sm font-medium tracking-tight">
    @if (is_array($getState()) || is_object($getState()))
        @php
            $counter = 0;
        @endphp
        @foreach ($getState() as $key => $value)
            @if ($counter < 5)
                <span
                    class="inline-block p-1 mr-1 rounded-md whitespace-normal text-gray-700 dark:text-gray-200 bg-gray-500/10">
                    {{ ucfirst($key) }}
                </span>
                @unless (is_array($value))
                    : {{ $value }}<br>
                @else
                    <span class="divide-x divide-solid divide-gray-200 dark:divide-gray-700">
                        @foreach ($value as $nestedValue)
                            {{ $nestedValue['id'] }}
                        @endforeach
                    </span><br>
                @endunless
            @endif
            @php
                $counter++;
            @endphp
        @endforeach
        @if (count($getState()) > 5)
            <div id="more2" style="display: none;">
                @foreach (array_slice($getState(), 5) as $key => $value)
                    <span id="{{ $key }}"
                        class="inline-block p-1 mr-1 rounded-md whitespace-normal text-gray-700 dark:text-gray-200 bg-gray-500/10">
                        {{ ucfirst($key) }}
                    </span>
                    @unless (is_array($value))
                        : {{ $value }}<br>
                    @else
                        <span class="divide-x divide-solid divide-gray-200 dark:divide-gray-700">
                            @foreach ($value as $nestedValue)
                                {{ $nestedValue['id'] }}
                            @endforeach
                        </span><br>
                    @endunless
                @endforeach
                <a href="#more"
                    class="text-sm text-right text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 block"
                    onclick="document.getElementById('more2').style.display = 'none'; document.getElementById('showMore2').style.display = 'block';">Tutup</a>
            </div>
            <a id="showMore2" href="#showMore2"
                class="text-sm text-right text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 block"
                onclick="document.getElementById('more2').style.display = 'block'; this.style.display = 'none';">Tampilkan
                Selengkapnya</a>
        @endif
    @endif
</div>
