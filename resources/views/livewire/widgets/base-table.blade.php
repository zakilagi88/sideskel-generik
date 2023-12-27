<!-- resources/views/livewire/widgets/table/base-table.blade.php -->

<div>
    <x-filament::section class="my-10">


        <div class=" max-w-screen-2xl">


            <div class="bg-white dark:bg-gray-800 relative overflow-hidden ">

                <div class="flex items-center justify-between d p-4">
                    <x-filament::input.wrapper>
                        <x-slot name="prefix">
                            <span>Berdasarkan Wilayah</span>
                        </x-slot>

                        <x-filament::input.select wire:model.live="wilayahId" id="wilayahId" class="form-control">
                            <option value="">Tidak ada yang dipilih</option>
                            @foreach ($wilayah as $wil)
                                <option value="{{ $wil->wilayah_id }}">{{ $wil->wilayah_nama }}</option>
                            @endforeach
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                    <div class="flex">
                        <!-- Bagian Pencarian -->
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-2 flex items-center pointer-events-none">
                                <x-filament::icon-button icon="fas-magnifying-glass" color=gray size=xs />
                            </div>
                            <input wire:model.debounce.300ms="search" type="text"
                                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full px-6 py-2 pl-10"
                                placeholder="Search" required="">
                        </div>
                    </div>

                </div>

                <!-- Tabel Umur -->
                <div class="overflow-x-auto mt-6 rounded-lg">
                    @if (count($data) > 0)
                        <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400 table-auto">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 uppercase tracking-wider border" rowspan="2">
                                        No
                                    </th>
                                    @foreach ($headers as $header)
                                        @if ($loop->first)
                                            <th scope="col" class="py-3 border" rowspan="2">
                                                {{ ucfirst($header) }}
                                            </th>
                                        @else
                                            <th scope="col" class="px-4 py-3 border" colspan="2">
                                                {{ ucfirst($header) }}
                                            </th>
                                        @endif
                                    @endforeach
                                </tr>
                                <tr>
                                    {{-- foreach nya hanya untuk selain keys pertama --}}
                                    @foreach ($headers as $header)
                                        @if ($loop->first)
                                            @continue
                                        @else
                                            <th scope="col" class="px-4 py-3 border">Jumlah</th>
                                            <th scope="col" class="px-4 py-3 border">Persentase</th>
                                        @endif
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $index => $item)
                                    <tr class="{{ $index % 2 === 0 ? 'bg-gray-100' : 'bg-white' }}">
                                        <td
                                            class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white border">
                                            {{ $index + 1 }}
                                        </td>

                                        @foreach ($item as $key => $value)
                                            @if ($loop->first)
                                                <td
                                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white border">
                                                    {{ $value }}
                                                </td>
                                                @continue
                                            @else
                                                <td class="px-4 py-3 border">
                                                    {{ $value }}
                                                </td>
                                                <td class="px-4 py-3 border">
                                                    @if ($value > 0)
                                                        {{ number_format(($value / $totals) * 100, 2) }}%
                                                    @else
                                                        0%
                                                    @endif
                                                </td>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-center text-gray-700 py-4">Data tidak ditemukan.</p>
                    @endif
                </div>

                <div class="py-4 px-3">
                    <div class="flex">
                        <div class="flex space-x-4 items-center mb-3">
                            <label class="w-32 text-sm font-medium text-gray-900">Per Halaman</label>
                            <select wire:model.live="perPage"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                    {{ $data->links('livewire.components.custom-pagination') }}
                </div>
            </div>
        </div>
    </x-filament::section>
</div>
