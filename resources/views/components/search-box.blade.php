 <div x-data="{
     query: '{{ request('search', '') }}',
 }" id="search-box">
     <div>
         <h3 class="text-lg font-semibold text-gray-900 mb-3">Search</h3>
         <div class="py-2 px-3 mb-3 items-center">
             <x-filament::input.wrapper>
                 <x-filament::input type="text" x-model="name" />
                 <x-slot name="suffix">
                     <x-filament::icon-button icon="fas-magnifying-glass"
                         x-on:click="$dispatch('search', { search : query })" label="search" />
                 </x-slot>
             </x-filament::input.wrapper>
         </div>

     </div>
 </div>
