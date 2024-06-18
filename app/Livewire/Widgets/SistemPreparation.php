<?php

namespace App\Livewire\Widgets;

use App\Facades\Deskel;
use App\Models\Penduduk;
use App\Models\User;
use App\Models\Wilayah;
use App\Settings\GeneralSettings;
use App\Settings\WebSettings;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;

class SistemPreparation extends Widget
{

    use HasWidgetShield;

    protected static ?int $sort = 1;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 2;

    public $currentStep = 1;
    public $totalSteps = 5;

    public $deskel;
    private $deskelId;
    private $wilayahCount;
    private $pendudukCount;
    private $accountCount;

    public array $initSetup = [];
    public array $completedSteps = [];
    public bool $allStepsCompleted = false;


    /**
     * @var view-string
     */
    protected static string $view = 'livewire.widgets.sistem-preparation';

    public static function canView(): bool
    {
        /** @var \App\Models\User */
        $auth = Filament::auth()->user();
        if ($auth->hasRole('Admin')) {
            return true;
        }
    }

    public function mount()
    {
        $this->deskel = Deskel::getFacadeRoot();
        $this->retrieveCompletedSteps();
        $this->updateCompletedSteps();
    }

    public function getColumnSpan(): int | string | array
    {
        return $this->columnSpan;
    }

    public function retrieveCompletedSteps()
    {
        if (Session::has('completed_steps')) {
            $this->completedSteps = Session::get('completed_steps');
            $this->allStepsCompleted = $this->checkAllStepsCompleted();
        } else {
            $this->initCompletedSteps();
        }
    }

    public function initCompletedSteps()
    {

        for ($i = 0; $i <= $this->totalSteps; $i++) {
            $this->completedSteps[$i] = false;
        }
    }

    #[On('update-step')]
    public function updateCompletedSteps()
    {
        $set = app(GeneralSettings::class)->toArray();

        $this->initSetup = $set['site_init'];
        $this->deskelId = $this->deskel?->deskel_id;
        $this->wilayahCount = Wilayah::exists();
        $this->pendudukCount = Penduduk::exists();
        $this->accountCount = User::count();

        $conditions = [
            0 => $this->initSetup[0] == true,
            1 => $this->initSetup[1] == true && !(is_null($this->deskelId)),
            2 => $this->initSetup[2] == true && $this->wilayahCount == true,
            3 => $this->initSetup[3] == true && $this->pendudukCount == true,
            4 => $this->initSetup[4] == true && $this->accountCount > 2,
        ];

        $this->completedSteps[5] = $this->completedSteps[4];

        foreach ($conditions as $step => $condition) {
            $this->completedSteps[$step] = $condition;
        }

        $this->allStepsCompleted = $this->checkAllStepsCompleted();

        Session::put('completed_steps', $this->completedSteps);
    }

    public function checkAllStepsCompleted()
    {
        foreach ($this->completedSteps as $step) {
            if (!$step) {
                return false;
            }
        }
        return true;
    }

    public function updateStep($stepId)
    {
        $set = app(GeneralSettings::class);

        $stepCheck = [];
        for ($i = 0; $i < $this->totalSteps; $i++) {
            if ($i == $stepId) {
                $stepCheck[$i] = true;
            } else {
                $stepCheck[$i] = $this->initSetup[$i];
            }
        }

        if ($this->completedSteps[$stepId] == false) {
            $set->fill(['site_init' => $stepCheck])->save();
        }

        $this->dispatch('update-step');
    }

    protected function getViewData(): array
    {

        return [
            'steps' => [
                [
                    'id' => 0,
                    'label' => 'Pengaturan Aplikasi',
                    'description' => 'Selamat datang di SIDeskel Generik. Silahkan Masuk ke Pengaturan Aplikasi untuk memulai konfigurasi sistem.',
                    'href' => route('filament.panel.pages.pengaturan-umum'),
                    'completed' => $this->completedSteps[0],
                    'icon' => 'fas-cogs',
                ],
                [
                    'id' => 1,
                    'label' => 'Profil Desa',
                    'description' => 'Langkah selanjutnya adalah mengisi profil Desa/Kelurahan Anda.',
                    'href' => route('filament.panel.deskel.resources.profil.edit', ['record' => ($this->deskel->first())]),
                    'completed' => $this->completedSteps[1],
                    'icon' => 'fas-city',
                ],
                [
                    'id' => 2,
                    'label' => 'Wilayah Administratif',
                    'description' => 'Inisiasi Wilayah sesuai kebutuhan Desa/Kelurahan Anda.',
                    'href' => route('filament.panel.index.resources.wilayah.index'),
                    'completed' => $this->completedSteps[2],
                    'icon' => 'fas-map-marked-alt',
                ],
                [
                    'id' => 3,
                    'label' => 'Data Kependudukan',
                    'description' => 'Kelola Data Kependudukan dengan mengelola Kartu Keluarga dan Penduduk.',
                    'href' => route('filament.panel.kependudukan.resources.keluarga.index'),
                    'completed' => $this->completedSteps[3],
                    'icon' => 'fas-people-roof',
                ],
                [
                    'id' => 4,
                    'label' => 'Peran dan Pengguna',
                    'description' => 'Atur kesesuaian peran dan akun yang diperlukan',
                    'href' => route('filament.panel.pengaturan.resources.peran.index'),
                    'completed' => $this->completedSteps[4],
                    'icon' => 'fas-users-gear',
                ],
                [
                    'id' => 5,
                    'label' => 'Selesai',
                    'description' => 'Langkah-langkah konfigurasi awal sistem telah selesai. Selamat menggunakan sistem!',
                    'href' => route('filament.panel.pages.dashboard'),
                    'completed' => $this->completedSteps[5],
                    'icon' => 'fas-check-circle',
                ],
            ],
        ];
    }


    #[On('complete-step')]
    public function completeStep(int $step)
    {
        app(GeneralSettings::class)->fill(['site_active' => true])->save();
        app(WebSettings::class)->fill(['web_active' => true])->save();

        $this->redirect(route('filament.panel.pages.dashboard'));
    }
}
