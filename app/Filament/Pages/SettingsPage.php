<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Throwable;

use function Filament\Support\is_app_url;

/**
 * @property ComponentContainer $form
 */
abstract class SettingsPage extends Page
{
    use CanUseDatabaseTransactions;
    use InteractsWithFormActions;
    use HasUnsavedDataChangesAlert;

    protected static string $settings;

    protected static string $webSettings = '';

    protected static string $view = 'filament.pages.settings-page';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->initializeExtraData();
        $settings = $this->loadSettings();
        $this->fillForm($settings);
    }

    protected function initializeExtraData(): void
    {
    }

    protected function loadSettings(): array
    {
        $settings = app(static::getSettings())->toArray();
        $webSettings = app(static::getWebSettings())->toArray();

        if (!is_null($webSettings)) {
            $settings['web_settings'][0] = $webSettings;
        }

        return $settings;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $data;
    }

    protected function fillForm(array $data): void
    {
        $data = $this->mutateFormDataBeforeFill($data);

        $this->form->fill($data);
    }


    public function save(): void
    {
        try {
            $this->beginDatabaseTransaction();

            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            $settings = app(static::getSettings());
            $settings->fill($data);
            $settings->save();

            $this->callHook('afterSave');

            $this->commitDatabaseTransaction();
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction() ?
                $this->rollBackDatabaseTransaction() :
                $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        $this->rememberData();

        $this->getSavedNotification()?->send();

        if ($redirectUrl = $this->getRedirectUrl()) {
            $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
        }
    }

    public function getSavedNotification(): ?Notification
    {
        $title = $this->getSavedNotificationTitle();

        if (blank($title)) {
            return null;
        }

        return Notification::make()
            ->success()
            ->title($title);
    }

    public function getSavedNotificationTitle(): ?string
    {
        return $this->getSavedNotificationMessage() ?? 'Saved successfully';
    }

    /**
     * @deprecated Use `getSavedNotificationTitle()` instead.
     */
    protected function getSavedNotificationMessage(): ?string
    {
        return null;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    public static function getSettings(): string
    {
        return static::$settings ?? (string) str(class_basename(static::class))
            ->beforeLast('Settings')
            ->prepend('App\\Settings\\')
            ->append('Settings');
    }

    public static function getWebSettings(): string
    {
        return static::$webSettings ?? (string) str(class_basename(static::class))
            ->beforeLast('Settings')
            ->prepend('App\\Settings\\')
            ->append('Settings');
    }

    /**
     * @return array<Action | ActionGroup>
     */
    public function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }

    public function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('Simpan'))
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    public function getSubmitFormAction(): Action
    {
        return $this->getSaveFormAction();
    }

    public function form(Form $form): Form
    {
        return $form;
    }

    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema($this->getFormSchema())
                    ->statePath('data')
                    ->columns(2)
                    ->inlineLabel($this->hasInlineLabels()),
            ),
        ];
    }

    public function getRedirectUrl(): ?string
    {
        return null;
    }
}