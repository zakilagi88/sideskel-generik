@php
    $data = $getState();
@endphp

<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">

    <div class="overflow-x-auto ">
        <div class=" -mx-4 sm:-mx-8 px-4 sm:px-8 py-4  ">
            <div class="inline-block min-w-full shadow-md rounded-2xl border-2 border-gray-50 overflow-hidden ">
                <table class="min-w-full leading-normal rounded-2xl">
                    <thead>
                        <tr>
                        <tr>
                            @foreach ($data[0] as $header)
                                <th
                                    class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-950 uppercase tracking-wider">
                                    {{ $header }}
                                </th>
                            @endforeach
                        </tr>

                        </tr>
                    </thead>
                    <tbody>
                        @if (count($data) > 1)
                            @foreach (array_slice($data, 1) as $index => $row)
                                <tr class="{{ $index % 2 === 0 ? 'even:bg-gray-100' : 'odd:bg-white' }}">
                                    @foreach ($row as $value)
                                        <td class="px-4 py-2 border-b border-gray-200 bg-white text-sm">
                                            {{ $value }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="{{ count($data[0]) }}"
                                    class="px-4 py-2 border-b border-gray-200 bg-white text-sm text-center">
                                    Belum Ada Data</td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>

    </div>



</x-dynamic-component>
