<?php

namespace App\Livewire\Widgets;

use App\Facades\Deskel;
use App\Models\Penduduk;
use App\Models\User;
use App\Models\Wilayah;
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

    public array $completedSteps = [];
    public bool $allStepsCompleted = false;


    /**
     * @var view-string
     */
    protected static string $view = 'livewire.widgets.sistem-preparation';

    public static function canView(): bool
    {
        if (Filament::auth()->user()->hasRole('Admin')) {
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

        for ($i = 1; $i <= $this->totalSteps; $i++) {
            $this->completedSteps[$i] = false;
        }
    }

    #[On('update-step')]
    public function updateCompletedSteps()
    {
        $this->deskelCount = $this->deskel->count();
        $this->wilayahCount = Wilayah::count();
        $this->pendudukCount = Penduduk::count();
        $this->accountCount = User::count();

        $conditions = [
            1 => $this->deskelCount > 0,
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
        if ($stepId == 4) {
            $this->completedSteps[4] = true;
        }

        $this->dispatch('update-step');
    }

    protected function getViewData(): array
    {

        return [
            'steps' => [
                [
                    'id' => 1,
                    'label' => 'Profil Desa',
                    'description' => 'Lengkapi data profil desa untuk memulai konfigurasi sistem.',
                    'href' => route('filament.admin.pages.deskel-profile'),
                    'completed' => $this->completedSteps[1],
                    'icon' => 'fas-city',
                ],
                [
                    'id' => 2,
                    'label' => 'Wilayah Administratif',
                    'description' => 'Konfigurasi wilayah administratif sesuai kebutuhan desa Anda.',
                    'href' => route('filament.admin.wilayah.resources.wilayahs.index'),
                    'completed' => $this->completedSteps[2],
                    'icon' => 'fas-map',
                ],
                [
                    'id' => 3,
                    'label' => 'Data Kependudukan',
                    'description' => 'Kelola data penduduk desa dengan menambahkan data kependudukan.',
                    'href' => route('filament.admin.kependudukan.resources.keluarga.index'),
                    'completed' => $this->completedSteps[3],
                    'icon' => 'fas-city',
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
                    'icon' => 'fas-building-circle-check',
                ],
            ],
        ];
    }


    #[On('complete-step')]
    public function completeStep(int $step)
    {
        $this->deskel->update(['status' => true]);
        $this->redirect(route('filament.admin.pages.dashboard'));
    }
}
