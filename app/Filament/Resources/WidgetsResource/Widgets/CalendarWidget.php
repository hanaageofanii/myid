<?php

namespace App\Filament\Widgets;

use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use App\Models\Event;
use Saade\FilamentFullCalendar\Data\EventData;
use Filament\Forms;
use Filament\Actions;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CalendarWidget extends FullCalendarWidget
{
    protected static ?int $sort = 1;
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Kalender Acara';

    public Model | string | null $model = Event::class;

    /**
     * Ambil daftar event dari database untuk ditampilkan di kalender.
     */
    public function fetchEvents(array $fetchInfo): array
    {
        return Event::query()
            ->where('starts_at', '>=', $fetchInfo['start'])
            ->where('ends_at', '<=', $fetchInfo['end'])
            ->get()
            ->map(fn (Event $event) => EventData::make()
                ->id((string) $event->id)
                ->title($event->name)
                ->start($event->starts_at)
                ->end($event->ends_at)
                ->url(route('filament.admin.resources.events.edit', $event->id))
            )
            ->toArray();
    }

    /**
     * Konfigurasi tampilan kalender.
     */
    public function config(): array
    {
        return [
            'firstDay' => 1, // Mulai dari Senin
            'headerToolbar' => [
                'left' => 'dayGridMonth,timeGridWeek,timeGridDay',
                'center' => 'title',
                'right' => 'prev,next today',
            ],
        ];
    }

    /**
     * Form untuk menambah/edit event.
     */
    public function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->label('Nama Event')
                ->required(),

            Forms\Components\Grid::make()
                ->schema([
                    Forms\Components\DateTimePicker::make('starts_at')
                        ->label('Mulai')
                        ->required(),

                    Forms\Components\DateTimePicker::make('ends_at')
                        ->label('Selesai')
                        ->required()
                        ->after('starts_at'),
                ]),
        ];
    }

    /**
     * Aksi di header widget (Tambah Event).
     */
    protected function headerActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Event')
                ->modalHeading('Buat Event Baru')
                ->form($this->getFormSchema())
                ->successNotificationTitle('Event berhasil ditambahkan')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['starts_at'] = Carbon::parse($data['starts_at'])->toDateTimeString();
                    $data['ends_at'] = Carbon::parse($data['ends_at'])->toDateTimeString();
                    return $data;
                })
                ->after(function () {
                    $this->dispatch('refreshCalendar'); // Refresh kalender setelah tambah event
                }),
        ];
    }

    /**
     * Aksi dalam modal event (Edit & Hapus).
     */
    protected function modalActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit Event')
                ->modalHeading('Edit Event')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['starts_at'] = Carbon::parse($data['starts_at'])->toDateTimeString();
                    $data['ends_at'] = Carbon::parse($data['ends_at'])->toDateTimeString();
                    return $data;
                })
                ->successNotificationTitle('Event berhasil diperbarui')
                ->after(function () {
                    $this->dispatch('refreshCalendar'); // Refresh kalender setelah edit
                }),

            Actions\DeleteAction::make()
                ->label('Hapus Event')
                ->modalHeading('Hapus Event?')
                ->successNotificationTitle('Event berhasil dihapus')
                ->after(function () {
                    $this->dispatch('refreshCalendar'); // Refresh kalender setelah hapus
                }),
        ];
    }
}
