@props([
    'heading' => null,
    'subheading' => null,
])


<div {{ $attributes->class(['fi-login-page']) }}>

    <section class="grid grid-cols-1 xs:grid-cols-1 sm:grid-cols-4 bg-primary-400 rounded-lg">


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


        <div class="relative col-span-2 hidden sm:col-span-2 sm:flex bg-white  text-center rounded-r-xl px-10">
            <div class=" left-0 right-0 flex justify-center items-center ">
                <img src="{{ asset('sites/illustration.png') }}" alt="" class="object-contain h-64 mr-2">
            </div>
            <div class="absolute bottom-0 left-0 right-0 flex justify-center items-center ">
                <img src="{{ asset('sites/logo-primary.png') }}" alt="" class="object-contain h-32 mr-2">
            </div>
        </div>

    </section>



</div>
