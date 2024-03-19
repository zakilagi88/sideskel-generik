<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class AuthProfile extends EditProfile
{

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pengguna')
                    ->aside()
                    ->extraAttributes([
                        'class' => 'fi-aside-section',
                    ])
                    ->description('Informasi pengguna yang akan digunakan untuk login ke aplikasi.')
                    ->schema([
                        Split::make([
                            FileUpload::make('avatar_url')
                                ->hiddenLabel()
                                ->disk('public')
                                ->directory('profile')
                                ->image()
                                ->imageResizeMode('contain')
                                ->imageEditor()
                                ->imageEditorAspectRatios([
                                    '9:16',
                                    '3:4',
                                    '1:1',
                                ])
                                ->imagePreviewHeight('300')
                                ->loadingIndicatorPosition('right')
                                ->panelAspectRatio('1:1')
                                ->alignCenter()
                                ->panelLayout('integrated')
                                ->removeUploadedFileButtonPosition('right')
                                ->uploadProgressIndicatorPosition('left'),
                            Group::make([
                                TextInput::make('username')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->label('Username'),
                                $this->getNameFormComponent(),
                                $this->getEmailFormComponent(),
                                ColorPicker::make('settings.color')
                                    ->label('Warna Utama')
                                    ->formatStateUsing(
                                        fn (?string $state): string => $state ?? config('filament.theme.colors.primary')
                                    )
                                    ->inlineLabel()
                            ]),

                        ]),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ]),

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
