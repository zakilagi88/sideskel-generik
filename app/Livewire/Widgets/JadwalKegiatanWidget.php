<?php

namespace App\Livewire\Widgets;

use App\Models\Dinamika;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class JadwalKegiatanWidget extends FullCalendarWidget
{
    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */
    public Model | string | null $model = Dinamika::class;

    public function getFormSchema(): array
    {
        return [
            TextInput::make('name'),

            Grid::make()
                ->schema([
                    DateTimePicker::make('starts_at'),

                    DateTimePicker::make('ends_at'),
                ]),
        ];
    }

    public function fetchEvents(array $fetchInfo): array
    {
        // You can use $fetchInfo to filter events by date.
        // This method should return an array of event-like objects. See: https://github.com/saade/filament-fullcalendar/blob/3.x/#returning-events
        // You can also return an array of EventData objects. See: https://github.com/saade/filament-fullcalendar/blob/3.x/#the-eventdata-class
        return [];
    }
}
