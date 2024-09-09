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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class AuthProfile extends EditProfile
{

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Pengguna')
                    ->aside()
                    ->extraAttributes(['class' => 'fi-aside-section'])
                    ->description('Informasi pengguna yang akan digunakan untuk login ke aplikasi.')
                    ->schema([
                        Group::make([
                            $this->getAvatarFormComponent()
                                ->columnSpan(1),
                            Group::make([
                                $this->getUsernameFormComponent(),
                                $this->getNameFormComponent(),
                                $this->getEmailFormComponent(),
                            ])->columnSpan(2),
                        ])->columns(3),
                    ]),
                Section::make('Ganti Password')
                    ->aside()
                    ->extraAttributes(['class' => 'fi-aside-section'])
                    ->description('Jika Anda ingin mengganti password, silahkan isi kolom berikut.')
                    ->schema([
                        Group::make([
                            $this->getOldPasswordFormComponent(),
                            $this->getPasswordFormComponent(),
                            $this->getPasswordConfirmationFormComponent(),
                        ]),
                    ]),

            ]);
    }

    protected function getAvatarFormComponent(): Component
    {
        return  FileUpload::make('avatar_url')
            ->hiddenLabel()
            ->alignCenter()
            ->disk('public')
            ->directory('profile')
            ->moveFiles()
            ->avatar()
            ->image()
            ->imageEditor()
            ->imageEditorAspectRatios([null, '16:9', '4:3', '1:1'])
            ->panelAspectRatio('2:3')
            ->panelLayout('integrated')
            ->imagePreviewHeight('300')
            ->loadingIndicatorPosition('right')
            ->removeUploadedFileButtonPosition('right')
            ->uploadProgressIndicatorPosition('left');
    }

    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->label(__('Username'))
            ->disabled()
            ->unique(ignoreRecord: true)
            ->required();
    }

    protected function getNameFormComponent(): Component
    {
        return parent::getNameFormComponent();
    }

    protected function getEmailFormComponent(): Component
    {
        return parent::getEmailFormComponent()
            ->label(__('Email'))
            ->required(false);
    }

    protected function getOldPasswordFormComponent(): Component
    {
        return TextInput::make('current_password')
            ->label(__('Kata Sandi Saat Ini'))
            ->password()
            ->revealable()
            ->currentPassword()
            ->required();
    }

    protected function getPasswordFormComponent(): Component
    {
        return parent::getPasswordFormComponent()
            ->label(__('Kata Sandi Baru'))
            ->revealable();
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return parent::getPasswordConfirmationFormComponent()
            ->label(__('Konfirmasi Kata Sandi Baru'))
            ->revealable();
    }
}
