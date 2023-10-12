<div>
    <header class="fi-simple-header py-8">
        <h1
            class="fi-header-heading text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl md:text-4xl sm:leading-none">
            {{ __('Edit Profile') }}
        </h1>
        <p class="fi-simple-header-subheading mt-2 text-left text-sm text-gray-500 dark:text-gray-400">
            {{ __('Ubah profil Anda dan atur preferensi akun Anda.') }}
        </p>

    </header>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" alignment="right" />

    </x-filament-panels::form>
</div>
