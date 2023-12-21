<?php

namespace App\Livewire\Components;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo;

class Settings extends PersonalInfo
{
    public ?array $data = [];
    public array $only = ['name', 'email'];

    protected function getProfileFormSchema()
    {
        $name = TextInput::make('name')
            ->required()
            ->label(__('filament-breezy::default.fields.name'));
        $email = TextInput::make('email')
            ->required()
            ->email()
            ->unique($this->userClass, ignorable: $this->user)
            ->label(__('filament-breezy::default.fields.email'));

        $username = TextInput::make('username')
            ->required()
            ->unique($this->userClass, ignorable: $this->user);

        if ($this->hasAvatars) {
            return [
                filament('filament-breezy')->getAvatarUploadComponent(),
                Group::make([
                    $name,
                    $email,
                    // $username
                ])->columnSpan(2),
            ];
        } else {
            return [
                Group::make([
                    $name,
                    $email,
                    // $username
                ])->columnSpan(3)
            ];
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                $this->getProfileFormSchema(),

            )->columns(3)
            ->statePath('data');
    }
}