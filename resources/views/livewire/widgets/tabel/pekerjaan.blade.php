<div>

    <section class="mt-10">
        <div class="mx-auto max-w-screen-2xl lg:px-10">
            <!-- Start coding here -->
            <div class="bg-white rounded-lg dark:bg-gray-800 relative shadow-lg sm:rounded-lg overflow-hidden">
                <div class="flex items-center justify-between d p-4">
                    <div class="flex">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-2 flex items-center pointer-events-none">
                                <x-filament::icon-button icon="fas-magnifying-glass" color=gray size=xs />
                            </div>
                            <input wire:model.live.debounce.300ms="search" type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full px-8 py-2.5 pl-10  "
                                placeholder="Search" required="">
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <div class="flex space-x-2 items-center">
                            <label class="w-full text-sm font-medium text-gray-900">Filter :</label>
                            <select
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                <option value="">All</option>
                                <option value="0">User</option>
                                <option value="1">Admin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 uppercase tracking-wider">
                                    No</th>
                                <th scope="col" class="py-3">Kategori Pekerjaan</th>
                                <th scope="col" class="px-4 py-3">Jumlah</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($this->pekerjaans as $pekerjaan)
                                <tr class="border-b dark:border-gray-700">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $loop->iteration }}
                                    </th>
                                    <th scope="row"
                                        class="py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $pekerjaan->pekerjaan }}</th>
                                    <td class="px-4 py-3">{{ $pekerjaan->total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="py-4 px-3">
                    <div class="flex">
                        <div class="flex space-x-4 items-center mb-3">
                            <label class="w-32 text-sm font-medium text-gray-900">Per Page</label>
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
                    {{ $this->pekerjaans->links() }}
                </div>
            </div>
        </div>
    </section>

</div>
