<?php

namespace App\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Auth\Login;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\ValidationException;

class CustomLogin extends Login
{

    protected static string $view = 'filament.pages.auth.login';

    public function mount(): void
    {
        if (Filament::auth()->check()) {
            redirect()->intended(Filament::getUrl());
        }
        $this->form->fill();
    }
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.username' => __('filament-panels::pages/auth/login.messages.failed'),
            'data.password' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }

    public function getTitle(): string|Htmlable
    {
        return __('Selamat Datang di Sistem Informasi Desa');
    }

    public function getHeading(): string|Htmlable
    {
        return __('Kelurahan Kuripan');
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('username')
            ->label('Username')
            ->required()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1])
            ->autocomplete();
    }
}
