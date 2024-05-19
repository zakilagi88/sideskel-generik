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
    private $deskelCount;
    private $wilayahCount;
    private $pendudukCount;
    private $accountCount;

    public bool $initSetup = false;
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
        $this->deskelCount = $this->deskel->deskel_id;
        $this->wilayahCount = Wilayah::count();
        $this->pendudukCount = Penduduk::count();
        $this->accountCount = User::count();

        $conditions = [
            0 => $this->initSetup == true,
            1 => !(is_null($this->deskelCount)),
            2 => $this->wilayahCount > 0,
            3 => $this->pendudukCount > 0,
        ];

        $this->completedSteps[4] = $this->completedSteps[3] && $this->completedSteps[4];

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

        if ($stepId == 0) {
            $set->fill(['site_init' => true])->save();
            $this->completedSteps[0] = true;
        } elseif ($stepId == 4) {
            $this->completedSteps[4] = true;
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
                    'href' => route('filament.admin.pages.pengaturan-umum'),
                    'completed' => $this->completedSteps[0],
                    'icon' => 'fas-city',
                ],
                [
                    'id' => 1,
                    'label' => 'Profil Desa',
                    'description' => 'Langkah selanjutnya adalah mengisi profil Desa/Kelurahan Anda.',
                    'href' => route('filament.admin.deskel.resources.profil.edit', ['record' => ($this->deskel->first())]),
                    'completed' => $this->completedSteps[1],
                    'icon' => 'fas-city',
                ],
                [
                    'id' => 2,
                    'label' => 'Wilayah Administratif',
                    'description' => 'Inisiasi Wilayah sesuai kebutuhan Desa/Kelurahan Anda.',
                    'href' => route('filament.admin.index.resources.wilayah.index'),
                    'completed' => $this->completedSteps[2],
                    'icon' => 'fas-map-location',
                ],
                [
                    'id' => 3,
                    'label' => 'Data Kependudukan',
                    'description' => 'Kelola Data Kependudukan dengan mengelola Kartu Keluarga dan Penduduk.',
                    'href' => route('filament.admin.kependudukan.resources.keluarga.index'),
                    'completed' => $this->completedSteps[3],
                    'icon' => 'fas-users',
                ],
                [
                    'id' => 4,
                    'label' => 'Peran dan Pengguna',
                    'description' => 'Atur kesesuaian peran dan akun yang diperlukan',
                    'href' => route('filament.admin.resources.peran.index'),
                    'completed' => $this->completedSteps[4],
                    'icon' => 'fas-users-gear',
                ],
                [
                    'id' => 5,
                    'label' => 'Selesai',
                    'description' => 'Langkah-langkah konfigurasi awal sistem telah selesai. Selamat menggunakan sistem!',
                    'href' => route('filament.admin.pages.dashboard'),
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

        $this->redirect(route('filament.admin.pages.dashboard'));
    }
}
