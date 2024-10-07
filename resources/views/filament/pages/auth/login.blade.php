@props([
    'heading' => null,
    'subheading' => null,
])


<div {{ $attributes->class(['fi-login-page']) }}>

    <section class="grid grid-cols-1 xs:grid-cols-1 sm:grid-cols-4 bg-primary-500 rounded-lg shadow-2xl">


        <div class="col-span-2 xs:col-span-1 sm:col-span-2 px-10 py-10 ">
            <x-filament-panels::header.simple :heading="$heading ??= $this->getHeading()" :logo="$this->hasLogo()" :subheading="$subheading ??= $this->getSubHeading()" />

            @if (filament()->hasRegistration())
                <x-slot name="subheading">
                    {{ __('filament-panels::pages/auth/login.actions.register.before') }}

                    {{ $this->registerAction }}
                </x-slot>
            @endif

            {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.before') }}

            <x-filament-panels::form wire:submit="authenticate" class="mt-10">
                {{ $this->form }}

                <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
            </x-filament-panels::form>

            {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.after') }}
        </div>

        <div class="relative col-span-2 hidden sm:col-span-2 sm:flex bg-secondary-400 text-center rounded-r-lg mx-0">
            <div class="flex justify-center items-center w-full h-full">
                <img src="{{ asset('storage/sites/sideskel.png') }}" alt="" class="object-contain h-80">
            </div>
        </div>


    </section>



</div>
