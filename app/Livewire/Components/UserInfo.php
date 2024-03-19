<?php

namespace App\Livewire\Components;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile;

class UserInfo extends EditProfile
{

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('avatar_url')
                    ->hiddenLabel()
                    ->extraAttributes(
                        ['class' => 'mt-1',]
                    )
                    ->extraInputAttributes(
                        ['class' => 'fi-pond-ta']
                    )
                    ->getUploadedFileNameForStorageUsing(
                        fn (TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                            ->prepend('gambar-penduduk-'),
                    )
                    ->disk('public')
                    ->directory('profile')
                    ->avatar()
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        '9:16',
                        '3:4',
                        '1:1',
                    ])
                    ->imagePreviewHeight('300')
                    ->loadingIndicatorPosition('right')
                    ->panelAspectRatio('3.5:7')
                    ->alignCenter()
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('right')
                    ->uploadProgressIndicatorPosition('left')
                    ->columnStart([
                        'lg' => 1,
                        'md' => 1,
                    ]),
                TextInput::make('username')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Username'),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                ColorPicker::make('settings.color')
                    ->label('Warna Utama')
                    ->formatStateUsing(
                        fn (?string $state): string => $state ?? config('filament.theme.colors.primary')
                    )
                    ->inlineLabel()
            ]);
    }

    protected function getNameFormComponent(): Component
    {
        return parent::getNameFormComponent();
    }

    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent();
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->revealable(false);
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return parent::getPasswordConfirmationFormComponent()
            ->revealable(false);
    }
}