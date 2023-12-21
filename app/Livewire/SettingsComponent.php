<?php

namespace App\Livewire;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo;
use Jeffgreco13\FilamentBreezy\Pages\MyProfilePage;

class SettingsComponent extends PersonalInfo
{
    public ?array $data = [];
    public array $only = ['name', 'email', 'username', 'settings'];

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
            ->hint('Username tidak dapat diubah')
            ->disabled()
            ->unique($this->userClass, ignorable: $this->user);

        $settings =
            ColorPicker::make('settings.color')
            ->label('Warna Utama')
            ->formatStateUsing(
                fn (?string $state): string => $state ?? config('filament.theme.colors.primary')
            )
            ->inlineLabel();


        if ($this->hasAvatars) {
            return [
                filament('filament-breezy')->getAvatarUploadComponent(),
                Group::make([
                    $name,
                    $username,
                    $email,
                    $settings,
                ])->columnSpan(2),
            ];
        } else {
            return [
                Group::make([
                    $name,
                    $username,
                    $email,
                    $settings,
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

    public function submit(): void
    {
        $data = collect($this->form->getState())->only($this->only)->all();
        $this->user->update($data);
        $this->redirect(request()->header('Referer'));
        $this->sendNotification();
    }
}
