<?php

namespace App\Filament\Clusters\Kependudukan\Resources\PendudukResource\Pages;

use App\Enums\Kependudukan\StatusDasarType;
use App\Filament\Clusters\Kependudukan\Resources\PendudukResource;
use App\Filament\Clusters\Kependudukan\Resources\PendudukResource\Widgets\PendudukOverview;
use App\Models\Dinamika;
use App\Models\Kepindahan;
use App\Models\Pendatang;
use App\Models\Penduduk;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions as ComponentsActions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListPenduduks extends ListRecords
{

    use ExposesTableToWidgets;

    protected static string $resource = PendudukResource::class;

    public Collection $statuswarga, $sementara;


    public function __construct()
    {
        $this->statuswarga = Penduduk::select('status_dasar', DB::raw('count(*) as status_count'))
            ->groupBy('status_dasar')->pluck('status_count', 'status_dasar');
        $this->sementara = Penduduk::select('status_penduduk', DB::raw('count(*) as sementara_count'))
            ->groupBy('status_penduduk')->pluck('sementara_count', 'status_penduduk');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Penduduk')
                ->url(route('filament.admin.kependudukan.resources.keluarga.create')),
            ExportAction::make()->exports([
                ExcelExport::make('table')->fromTable()->askForFilename()
                    ->askForWriterType(),
                ExcelExport::make('form')->fromForm()->askForFilename()
                    ->askForWriterType(),
            ])
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PendudukOverview::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'Warga' => Tab::make()->modifyQueryUsing(fn (Builder $query) => ($query->where('status_dasar', 'HIDUP')))->badge($this->statuswarga[StatusDasarType::HIDUP->value] ?? '0')->icon('fas-children')->badgeColor('primary'),
            'Sementara' => Tab::make('Sementara')->modifyQueryUsing(fn (Builder $query) => ($query->where('status_penduduk', 'Sementara')))->badge($this->sementara['Sementara'] ?? '0')->icon('fas-people-group')->badgeColor('success'),
            'Pindah' => Tab::make()->modifyQueryUsing(function (Builder $query) {
                $query->where('status_dasar', 'HIDUP')->whereHas('dinamikas', function ($query) {
                    $query->where('dinamika_type', Kepindahan::class);
                });
            })
                ->label('Pindah')
                ->badge(Dinamika::where('dinamika_type', Kepindahan::class)->count())
                ->icon('fas-person-walking-dashed-line-arrow-right')->badgeColor('warning'),
            'Pendatang' => Tab::make()->modifyQueryUsing(function (Builder $query) {
                $query->where('status_dasar', 'HIDUP')->whereHas('dinamikas', function ($query) {
                    $query->where('dinamika_type', Pendatang::class);
                });
            })->badge(
                Dinamika::where('dinamika_type', Pendatang::class)->count()
            )->icon('fas-arrows-down-to-people')->badgeColor('info'),
            'Meninggal' => Tab::make()->modifyQueryUsing(function (Builder $query) {
                $query->where('status_dasar', 'MENINGGAL');
            })->badge($this->statuswarga[StatusDasarType::MENINGGAL->value] ?? '0')->icon('fas-person-falling-burst')->badgeColor('danger'),
        ];
    }
}
