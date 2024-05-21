<?php

namespace App\Livewire\Widgets;

use App\Filament\Clusters\HalamanDesa\Resources\JadwalKegiatanResource;
use App\Models\Deskel\JadwalKegiatan;
use Saade\FilamentFullCalendar\Actions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class JadwalKegiatanWidget extends FullCalendarWidget
{
    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */
    public Model | string | null $model = JadwalKegiatan::class;

    public function getFormSchema(): array
    {
        return [
            Hidden::make('user_id')
                ->default(auth()->id()),

            TextInput::make('nama_kegiatan'),

            TextInput::make('tempat_kegiatan'),

            Grid::make()
                ->schema([
                    DateTimePicker::make('tgl_mulai')
                        ->label('Tanggal Mulai')
                        ->placeholder('Pilih Tanggal Mulai')
                        ->required(),
                    DateTimePicker::make('tgl_selesai')
                        ->label('Tanggal Selesai')
                        ->placeholder('Pilih Tanggal Selesai')
                        ->required(),
                ]),

            Toggle::make('status')
                ->onIcon('fas-check')
                ->onColor('success')
                ->offIcon('fas-times')
                ->offColor('danger')
                ->default(true),
        ];
    }

    public function getColumnSpan(): int | string | array
    {
        return 2;
    }

    public function eventDidMount(): string
    {
        return <<<JS
        function({ event, timeText, isStart, isEnd, isMirror, isPast, isFuture, isToday, el, view }){

            
            el.setAttribute("x-tooltip", "tooltip");
            el.setAttribute("x-data", "{ tooltip: 'Mulai Pada "+event.start+" - Berakhir Pada "+event.end+"'}");
        }
    JS;
    }

    public function fetchEvents(array $fetchInfo): array
    {
        // You can use $fetchInfo to filter events by date.
        // This method should return an array of event-like objects. See: https://github.com/saade/filament-fullcalendar/blob/3.x/#returning-events
        // You can also return an array of EventData objects. See: https://github.com/saade/filament-fullcalendar/blob/3.x/#the-eventdata-class
        return JadwalKegiatan::query()
            ->where('tgl_mulai', '>=', $fetchInfo['start'])
            ->where('tgl_selesai', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn (JadwalKegiatan $jk) => EventData::make()
                    ->id($jk->id)
                    ->title($jk->nama_kegiatan)
                    ->allDay(false)
                    ->borderColor('info')
                    ->backgroundColor('primary')
                    ->textColor('white')
                    ->start($jk->tgl_mulai)
                    ->end($jk->tgl_selesai)
                    ->url(url: JadwalKegiatanResource::getUrl(name: 'edit', parameters: ['record' => $jk]), shouldOpenUrlInNewTab: false)
            )
            ->toArray();
    }

    protected function headerActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(
                    function ($data) {
                        return [
                            ...$data,
                            'user_id' => $data['user_id'] ?? auth()->id(),
                        ];
                    }
                ),
        ];
    }
}
