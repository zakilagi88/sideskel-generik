<?php

namespace App\Filament\Resources\PendudukResource\Pages;

use App\Enum\Penduduk\Status;
use App\Filament\Resources\PendudukResource;
use App\Filament\Resources\PendudukResource\Widgets\PendudukOverview;
use App\Models\Penduduk;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Actions as ComponentsActions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
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

    public Collection $statuswarga;


    public function __construct()
    {
        $this->statuswarga = Penduduk::select('status', DB::raw('count(*) as status_count'))->groupBy('status')->pluck('status_count', 'status');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Penduduk'),
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
            'Semua' => Tab::make('Semua')->icon('fas-people-group')->badge($this->statuswarga->sum())->badgeColor('primary'),
            'Warga' => Tab::make()->modifyQueryUsing(fn (Builder $query) => ($query->where('status', 'YA')))->badge($this->statuswarga[Status::YA->value] ?? '0')->icon('fas-children')->badgeColor('success'),
            'Tidak' => Tab::make()->modifyQueryUsing(function (Builder $query) {
                $query->where('status', 'TIDAK');
            })->label('Pindah')->badge($this->statuswarga[Status::TIDAK->value] ?? '0')->icon('fas-person-walking-dashed-line-arrow-right')->badgeColor('warning'),
            'Pendatang' => Tab::make()->modifyQueryUsing(function (Builder $query) {
                $query->where('status', 'PENDATANG');
            })->badge($this->statuswarga[Status::PENDATANG->value] ?? '0')->icon('fas-arrows-down-to-people')->badgeColor('info'),
            'Meninggal' => Tab::make()->modifyQueryUsing(function (Builder $query) {
                $query->where('status', 'MENINGGAL');
            })->badge($this->statuswarga[Status::MENINGGAL->value] ?? '0')->icon('fas-person-falling-burst')->badgeColor('danger'),
        ];
    }
}
