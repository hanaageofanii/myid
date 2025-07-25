<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvRekeningResource\Pages;
use App\Filament\Resources\GcvRekeningResource\RelationManagers;
use App\Models\gcv_rekening;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Models\form_dp;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\Exception;
use Filament\Tables\Actions\ForceDeleteAction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;

class GcvRekeningResource extends Resource
{
    protected static ?string $model = gcv_rekening::class;

    // protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = "GCV";
    protected static ?string $pluralLabel = "Rekening";
    protected static ?string $navigationLabel = "Kasir > Rekening";
    protected static ?string $pluralModelLabel = 'Daftar Rekening';
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
        protected static ?int $navigationSort = 15;

public static function form(Form $form): Form
{
    return $form->schema([
        Wizard::make([
            Step::make('Perusahaan & Bank')->schema([
                Select::make('nama_perusahaan')
                    ->label('Nama Perusahaan')
                    ->options([
                        'langgeng_pertiwi_development' => 'PT. Langgeng Pertiwi Development',
                        'agung_purnama_bakti' => 'PT. Agung Purnama Bakti',
                        'purnama_karya_bersama' => 'PT. Purnama Karya Bersama',
                    ])
                    ->required()
                        ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 2','Kasir 1']);
                    })()),   

                Select::make('bank')
                    ->label('Bank')
                    ->options([
                        'btn_karawang' => 'BTN Karawang',
                        'btn_cikarang' => 'BTN Cikarang',
                        'btn_bekasi' => 'BTN Bekasi',
                        'bjb_cikarang' => 'BJB Cikarang',
                        'bri_pekayon' => 'BRI Pekayon',
                        'bjb_syariah' => 'BJB Syariah',
                        'btn_cibubur' => 'BTN Cibubur',
                        'bni_kuningan' => 'BNI Kuningan',
                        'mandiri_cikarang' => 'Mandiri Cikarang',
                    ])
                    ->required()
                        ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 2','Kasir 1']);
                    })()),   
            ]),

            Step::make('Jenis & Rekening')->schema([
                Select::make('jenis')
                    ->label('Jenis')
                    ->options([
                        'operasional' => 'Operasional',
                        'escrow' => 'Escrow',
                    ])
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 2','Kasir 1']);
                    })()),   

                TextInput::make('rekening')
                    ->label('No. Rekening')
                    ->numeric()
                    ->required()
                        ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 2','Kasir 1']);
                    })()),   
            ]),
        ])
        ->columnSpanFull(),
    ]);
}
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_perusahaan')->label('Nama Perusahaan')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'langgeng_pertiwi_development' => 'PT. Langgeng Pertiwi Development',
                    'agung_purnama_bakti' => 'PT. Agung Purnama Bakti',
                    'purnama_karya_bersama' => 'PT. Purnama Karya Bersama',
                    default => $state,
                })->searchable(),

                Tables\Columns\TextColumn::make('bank')->label('Bank')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'btn_karawang' => 'BTN Karawang',
                    'btn_cikarang' => 'BTN Cikarang',
                    'btn_bekasi' => 'BTN Bekasi',
                    'bjb_cikarang' => 'BJB Cikarang',
                    'bri_pekayon' => 'BRI Pekayon',
                    'bjb_syariah' => 'BJB Syariah',
                    'btn_cibubur' => 'BTN Cibubur',
                    'bni_kuningan' => 'BNI Kuningan',
                    'mandiri_cikarang' => 'Mandiri Cikarang',
                    default => $state,
                })->searchable(),

                Tables\Columns\TextColumn::make('jenis')->label('Jenis')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'operasional' => 'Operasional',
                    'escrow' => 'Escrow',
                    default => $state,
                })->searchable(),

                Tables\Columns\TextColumn::make('rekening')->label('No. Rekening')
                ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('bank')
                    ->label('Bank')
                    ->options([
                        'btn_karawang' => 'BTN Karawang',
                        'btn_cikarang' => 'BTN Cikarang',
                        'btn_bekasi' => 'BTN Bekasi',
                        'bjb_cikarang' => 'BJB Cikarang',
                        'bri_pekayon' => 'BRI Pekayon',
                        'bjb_syariah' => 'BJB Syariah',
                        'btn_cibubur' => 'BTN Cibubur',
                        'bni_kuningan' => 'BNI Kuningan',
                        'mandiri_cikarang' => 'Mandiri Cikarang',
                        ]) ->native(false),

                Tables\Filters\TrashedFilter::make()
                    ->label('Data yang dihapus')
                    ->native(false),

                Tables\Filters\SelectFilter::make('nama_perusahaan')
                    ->label('Nama Perusahaan')
                    ->options([
                        'langgeng_pertiwi_development' => 'PT. Langgeng Pertiwi Development',
                        'agung_purnama_bakti' => 'PT. Agung Purnama Bakti',
                        'purnama_karya_bersama' => 'PT. Purnama Karya Bersama',
                    ])
                    ->native(false),


                Tables\Filters\SelectFilter::make('jenis')
                    ->label('Jenis')
                    ->options([
                        'operasional' => 'Operasional',
                        'escrow' => 'Escrow',
                    ])
                    ->native(false),

                 Filter::make('created_from')
                    ->label('Dari Tanggal')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Dari')
                            ->displayFormat('Y-m-d'),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when($data['created_from'] ?? null, fn ($q) =>
                            $q->whereDate('created_at', '>=', $data['created_from'])
                        )
                    ),

                Filter::make('created_until')
                    ->label('Sampai Tanggal')
                    ->form([
                        DatePicker::make('created_until')
                            ->label('Sampai')
                            ->displayFormat('Y-m-d'),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when($data['created_until'] ?? null, fn ($q) =>
                            $q->whereDate('created_at', '<=', $data['created_until'])
                        )
                    ),

            ], layout: FiltersLayout::AboveContent)
            ->filtersFormMaxHeight('400px')
            ->filtersFormColumns(3)
            ->filtersFormWidth(MaxWidth::FourExtraLarge)

            ->actions([
                ActionGroup::make([
                    ViewAction::make()
                        ->color('success')
                        ->label('Lihat'),
                    EditAction::make()
                        ->color('info')
                        ->label('Ubah')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Rekening Diperbarui')
                                ->body('Data Rekening telah berhasil disimpan.')),
                                DeleteAction::make()
                                ->color('danger')
                                ->label(fn ($record) => "Hapus Blok {$record->rekening}")
                                ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok {$record->rekening}")
                                ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->rekening}?")
                                ->successNotification(
                                    Notification::make()
                                        ->success()
                                        ->title('Data Rekening Dihapus')
                                        ->body('Data Rekening telah berhasil dihapus.')),
                    // RestoreAction::make()
                    //     ->label('Pulihkan')
                    //     ->successNotificationTitle('Data berhasil dipulihkan')
                    //     ->successRedirectUrl(route('filament.admin.resources.audits.index')),
                    Tables\Actions\RestoreAction::make()
                    ->color('info')
                    ->label(fn ($record) => "Kembalikan {$record->rekening}")
                    ->modalHeading(fn ($record) => "Konfirmasi Kembalikan Blok{$record->rekening}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengembalikan blok {$record->rekening}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Rekening')
                            ->body('Data Rekening berhasil dikembalikan.')
                    ),
                    Tables\Actions\ForceDeleteAction::make()
                    ->color('primary')
                    ->label(fn ($record) => "Hapus Permanent {$record->rekening}")
                    ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok Permanent{$record->rekening}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus blok secara permanent {$record->rekening}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Rekening')
                            ->body('Data Rekening berhasil dihapus secara permanen.')
                    ),
                    ])->button()->label('Action'),
                ], position: ActionsPosition::BeforeCells)

                ->groupedBulkActions([
                    BulkAction::make('delete')
                        ->label('Hapus')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Rekening')
                                ->body('Data Rekening berhasil dihapus.'))
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),

                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Rekening')
                                ->body('Data Rekening berhasil dihapus secara permanen.'))                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->forceDelete()),

                    BulkAction::make('export')
                        ->label('Download Data')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('info')
                        ->action(fn (Collection $records) => static::exportData($records)),

                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Kembalikan Data')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->button()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Rekening')
                                ->body('Data Rekening berhasil dikembalikan.')),
                ]);

    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function exportData(Collection $records)
    {
        $csvData = "Id, Nama Perusahaan, Bank, Jenis, No. Rekening\n";

        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->nama_perusahaan}, {$record->bank}, {$record->jenis}, {$record->rekening}\n";
        }

        return response()->streamDownload(fn () => print($csvData), 'Rekening.csv');
    }

    public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery()
        ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);

    // /** @var \App\Models\User|null $user */
    // $user = Auth::user();

    // if ($user) {
    //     if ($user->hasRole('Marketing')) {
    //         $query->where(function ($q) {
    //             $q->whereNull('kpr_status')
    //                 ->orWhere('kpr_status', '!=', 'akad');
    //         });
    //     } elseif ($user->hasRole(['Legal officer','Legal Pajak'])) {
    //         $query->where('kpr_status', 'akad');
    //     }
    // }

    return $query;
}
public static function getPages(): array
    {
        return [
            'index' => Pages\ListGcvRekenings::route('/'),
            'create' => Pages\CreateGcvRekening::route('/create'),
            'edit' => Pages\EditGcvRekening::route('/{record}/edit'),
        ];
    }
}