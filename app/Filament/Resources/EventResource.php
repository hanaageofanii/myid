<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Widgets\CalendarWidget;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\DeleteAction;
use Filament\Notifications\Notification;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected function getHeaderWidgets(): array
    {
        return [
            CalendarWidget::class, 
        ];
    }

    /**
     * Schema form untuk membuat atau mengedit event.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Event')
                    ->required()
                    ->maxLength(255),

                Forms\Components\DateTimePicker::make('starts_at')
                    ->label('Tanggal Mulai')
                    ->required(),

                Forms\Components\DateTimePicker::make('ends_at')
                    ->label('Tanggal Selesai')
                    ->required()
                    ->after('starts_at'),
                Forms\Components\TextInput::make('keterangan')
                ->label('Keterangan')
                ->maxLength('255')
            ]);
    }

    /**
     * Schema tabel untuk menampilkan event.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Event')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('starts_at')
                    ->label('Tanggal Mulai')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('ends_at')
                    ->label('Tanggal Selesai')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('keterangan')
                ->label('Keterangan')
                ->sortable(),
            ])
            ->filters([])
            ->actions([
                Actions\EditAction::make(),
                DeleteAction::make()
                        ->color('danger')
                        ->label(fn ($record) => "Hapus Event {$record->name}")
                        ->modalHeading(fn ($record) => "Konfirmasi Hapus Event {$record->name}")
                        ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus Event {$record->siteplan}?")
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data KPR Dihapus')
                                ->body('Data KPR telah berhasil dihapus.')),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ]);
    }

    /**
     * Relasi yang digunakan dalam resource ini.
     */
    public static function getRelations(): array
    {
        return [];
    }

    public static function getWidgets(): array
    {
        return [
            CalendarWidget::class,
        ];
    }

    /**
     * Halaman yang tersedia dalam resource ini.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
