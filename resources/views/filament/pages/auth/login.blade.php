@props([
    'heading' => null,
    'subheading' => null,
])


<div {{ $attributes->class(['fi-login-page']) }}>

    <section class="grid grid-cols-3 gap-6">
        <div class="col-span-2">
            <x-filament-panels::header.simple :heading="$heading ??= $this->getHeading()" :logo="$this->hasLogo()" :subheading="$subheading ??= $this->getSubHeading()" />

            @if (filament()->hasRegistration())
                <x-slot name="subheading">
                    {{ __('filament-panels::pages/auth/login.actions.register.before') }}
  
                    {{ $this->registerAction }}
                </x-slot>
            @endif

            {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.before') }}

            <x-filament-panels::form wire:submit="authenticate">
                {{ $this->form }}

                <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
            </x-filament-panels::form>

            {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.after') }}
        </div>

        <div class="col-span-1">
            <div class="flex flex-col items-center justify-center">
                <h3 class="text-lg font-semibold mb-4">Welcome</h3>
                <img src="https://via.placeholder.com/150" alt="Placeholder Image" class="mb-4">
                <p class="text-sm text-gray-600">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin
                    mollis
                    metus vel lorem sodales, sed varius libero ultrices. Integer sed justo vitae lectus ullamcorper
                    vestibulum.</p>
            </div>
            <!-- Right section content goes here -->
            @if (!$this instanceof \Filament\Tables\Contracts\HasTable)
                <x-filament-actions::modals />
            @endif
        </div>
    </section>
</div>
