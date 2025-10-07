<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvDatatanahResource\Pages;
use App\Filament\Resources\GcvDatatanahResource\RelationManagers;
use App\Models\gcv_datatanah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Validation\Rule;
use App\Filament\Resources\GcvDatatanahResource\Widgets\gcv_datatanahStats;


class GcvDatatanahResource extends Resource
{
    protected static ?string $model = gcv_datatanah::class;
    protected static string $resource = GcvDatatanahResource::class;
    protected static ?string $title = "Data Tanah";
    protected static ?string $navigationGroup = "GCV";
    protected static ?string $pluralLabel = "Data Tanah";
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Legal > Data Tanah';
    protected static ?string $pluralModelLabel = 'Data Tanah';
    protected static ?int $navigationSort = 13;
     protected static bool $isScopedToTenant = false;
      protected static ?string $tenantOwnershipRelationshipName = 'team';

    protected static ?string $tenantRelationshipName = 'team';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Informasi Data Tanah')
                    ->columns(2)
                    ->schema([
                        TextInput::make('no_bidang')
                        ->required()
                        ->label('No. Bidang')
                        ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                        TextInput::make('nama_pemilik_asal')
                        ->required()
                        ->label('Nama Pemilik Asal')
                        ->disabled(fn () => ! (function (){
                             /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                        TextInput::make('alas_hak')
                        ->required()
                        ->label('Alas Hak')
                        ->disabled(fn () => ! (function (){
                             /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                        TextInput::make('luas_surat')
                        ->required()
                        ->label('Luas Surat')
                        ->disabled(fn () => ! (function (){
                             /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                        TextInput::make('luas_ukur')
                        ->required()
                        ->columnSpanFull()
                        ->label('Luas Ukur')
                        ->disabled(fn () => ! (function (){
                             /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),
                        ]),

                    Step::make('Informasi Harga Jual')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nop')
                        ->label('NOP')
                        ->disabled(fn () => ! (function (){
                             /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                        TextInput::make('harga_jual')
                        ->label('Harga Jual')
                        ->disabled(fn () => ! (function (){
                             /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                        TextInput::make('sph')
                        ->label('SPH')
                        ->disabled(fn () => ! (function (){
                             /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                        TextInput::make('notaris')
                        ->label('Notaris')
                        ->disabled(fn () => ! (function (){
                             /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                        TextInput::make('catatan')
                        ->label('Catatan')
                        ->columnSpanFull()
                        ->disabled(fn () => ! (function (){
                             /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),
                        ]),

                    Step::make('Informasi Dokumen')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('up_sertifikat')
                            ->label('Upload Sertifikat')
                            ->disk('public')->multiple()->nullable()
                            ->downloadable()->previewable(false)
                            ->disabled(fn () => ! (function (){
                             /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                        FileUpload::make('up_nop')
                            ->label('Upload NOP')
                            ->disk('public')->multiple()->nullable()
                            ->downloadable()->previewable(false)
                            ->disabled(fn () => ! (function (){
                             /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                        FileUpload::make('up_datadiri')
                            ->label('Upload Data Diri')
                            ->disk('public')->multiple()->nullable()
                            ->downloadable()->previewable(false)
                            ->disabled(fn () => ! (function (){
                             /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                        FileUpload::make('up_sph')
                            ->label('Upload SPH')
                            ->disk('public')->multiple()->nullable()
                            ->downloadable()->previewable(false)
                            ->disabled(fn () => ! (function (){
                             /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                        FileUpload::make('up_tambahan_lainnya')
                            ->label('Upload TambahanLainnya')
                            ->columnSpanFull()
                            ->disk('public')->multiple()->nullable()
                            ->downloadable()->previewable(false)
                            ->disabled(fn () => ! (function (){
                             /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),
                    ])
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_bidang')->label('No. Bidang')->searchable(),
                TextColumn::make('nama_pemilik_asal')->label('Nama Pemilik Asal')->searchable(),
                TextColumn::make('alas_hak')->label('Alas Hak')->searchable(),
                TextColumn::make('luas_surat')->label('Luas Surat')->searchable(),
                TextColumn::make('luas_ukur')->label('Luas Ukur')->searchable(),

                TextColumn::make('nop')->label('NOP')->searchable(),
                TextColumn::make('harga_jual')->label('Harga Jual')->searchable(),
                TextColumn::make('sph')->label('SPH')->searchable(),
                TextColumn::make('notaris')->label('Notaris')->searchable(),
                TextColumn::make('catatan')->label('Catatan')->searchable(),

                TextColumn::make('up_sertifikat')
                ->label('File Sertifikat')
                ->formatStateUsing(function ($record) {
                    if (!$record->up_sertifikat) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->up_sertifikat) ? $record->up_sertifikat : json_decode($record->up_sertifikat, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $files = [];
                    }

                    $output = '';
                    foreach ($files as $file) {
                        $url = Storage::url($file);
                        $output .= '<a href="' . $url . '" target="_blank">Lihat</a> | <a href="' . $url . '" download>Download</a><br>';
                    }

                    return $output ?: 'Tidak Ada Dokumen';
                })
                ->html()
                ->sortable(),

                TextColumn::make('up_nop')
                ->label('File NOP')
                ->formatStateUsing(function ($record) {
                    if (!$record->up_nop) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->up_nop) ? $record->up_nop: json_decode($record->up_nop, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $files = [];
                    }

                    $output = '';
                    foreach ($files as $file) {
                        $url = Storage::url($file);
                        $output .= '<a href="' . $url . '" target="_blank">Lihat</a> | <a href="' . $url . '" download>Download</a><br>';
                    }

                    return $output ?: 'Tidak Ada Dokumen';
                })
                ->html()
                ->sortable(),

                TextColumn::make('up_datadiri')
                ->label('File Data Diri')
                ->formatStateUsing(function ($record) {
                    if (!$record->up_datadiri) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->up_datadiri) ? $record->up_datadiri : json_decode($record->up_datadiri, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $files = [];
                    }

                    $output = '';
                    foreach ($files as $file) {
                        $url = Storage::url($file);
                        $output .= '<a href="' . $url . '" target="_blank">Lihat</a> | <a href="' . $url . '" download>Download</a><br>';
                    }

                    return $output ?: 'Tidak Ada Dokumen';
                })
                ->html()
                ->sortable(),


                TextColumn::make('up_sph')
                ->label('File SPH')
                ->formatStateUsing(function ($record) {
                    if (!$record->up_sph) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->up_sph) ? $record->up_sph : json_decode($record->up_sph, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $files = [];
                    }

                    $output = '';
                    foreach ($files as $file) {
                        $url = Storage::url($file);
                        $output .= '<a href="' . $url . '" target="_blank">Lihat</a> | <a href="' . $url . '" download>Download</a><br>';
                    }

                    return $output ?: 'Tidak Ada Dokumen';
                })
                ->html()
                ->sortable(),

                TextColumn::make('up_tambahan_lainnya')
                ->label('File Tanah Lainnya')
                ->formatStateUsing(function ($record) {
                    if (!$record->up_tambahan_lainnya) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->up_tambahan_lainnya) ? $record->up_tambahan_lainnya : json_decode($record->up_tambahan_lainnya, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $files = [];
                    }

                    $output = '';
                    foreach ($files as $file) {
                        $url = Storage::url($file);
                        $output .= '<a href="' . $url . '" target="_blank">Lihat</a> | <a href="' . $url . '" download>Download</a><br>';
                    }

                    return $output ?: 'Tidak Ada Dokumen';
                })
                ->html()
                ->sortable(),
            ]) ->defaultSort('no_bidang', 'asc')
            ->headerActions([
                Action::make('count')
                    ->label(fn ($livewire): string => 'Total: ' . $livewire->getFilteredTableQuery()->count())
                    ->disabled(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
                ->label('Data yang dihapus')
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
                                ->title('Data Tanah Diperbarui')
                                ->body('Data Tanah  telah berhasil disimpan.')),
                    DeleteAction::make()
                                ->color('danger')
                                ->label(fn ($record) => "Hapus Data Tanah {$record->no_bidang}")
                                ->modalHeading(fn ($record) => "Konfirmasi Hapus Data Tanah {$record->no_bidang}")
                                ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus Data Tanah {$record->no_bidang}?")
                                ->successNotification(
                                    Notification::make()
                                        ->success()
                                        ->title('Data Tanah  Dihapus')
                                        ->body('Data Tanah telah berhasil dihapus.')),
                    // RestoreAction::make()
                    //     ->label('Pulihkan')
                    //     ->successNotificationTitle('Data berhasil dipulihkan')
                    //     ->successRedirectUrl(route('filament.admin.resources.no_bidangs.index')),
                    Tables\Actions\RestoreAction::make()
                    ->color('info')
                    ->label(fn ($record) => "Kembalikan {$record->no_bidang}")
                    ->modalHeading(fn ($record) => "Konfirmasi Kembalikan Data Tanah{$record->no_bidang}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengembalikan Data Tanah {$record->no_bidang}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Tanah')
                            ->body('Data Tanah berhasil dikembalikan.')
                    ),
                    Tables\Actions\ForceDeleteAction::make()
                    ->color('primary')
                    ->label(fn ($record) => "Hapus Permanent {$record->no_bidang}")
                    ->modalHeading(fn ($record) => "Konfirmasi Hapus Data Tanah Permanent{$record->no_bidang}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus Data Tanah secara permanent {$record->no_bidang}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Tanah')
                            ->body('Data Tanah  berhasil dihapus secara permanen.')
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
                                ->title('Data Tanah')
                                ->body('Data Tanah berhasil dihapus.'))
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),

                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Tanah')
                                ->body('Data Tanah berhasil dihapus secara permanen.'))
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->forceDelete()),

                    BulkAction::make('export')
                        ->label('Download Data')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('info')
                        ->action(fn (Collection $records) => static::exportData($records)),

                    BulkAction::make('print')
                    ->label('Print Data')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->action(function (Collection $records) {
                        session(['print_records' => $records->pluck('id')->toArray()]);

                        return redirect()->route('datatanah.print');
                    }),

                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Kembalikan Data')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->button()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Tanah')
                                ->body('Data Tanah berhasil dikembalikan.')),
                ]);

    }

    public static function exportData(Collection $records)
    {
        $csvData = "ID, No. Bidang, Nama Pemilik Alas, Alas Hak, Luas Surat, Luas Ukur, nop, harga_jual, sph, notaris, catatan\n";

        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->no_bidang}, $record->nama_pemilik_asal, {$record->alas_hak}, {$record->luas_surat}, {$record->luas_ukur}, {$record->nop}, {$record->harga_jual}, {$record->sph}, {$record->notaris}, {$record->catatan}\n";
        }

        return response()->streamDownload(fn () => print($csvData), 'DataTanah.csv');
    }


    public static function getRelations(): array
    {
        return [

        ];
    }


    public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ])
        ->where('team_id', filament()->getTenant()->id); // filter data sesuai tenant
}

        public static function getWidgets(): array
        {
            return [
                gcv_datatanahStats::class,
            ];
        }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGcvDatatanahs::route('/'),
            'create' => Pages\CreateGcvDatatanah::route('/create'),
            'edit' => Pages\EditGcvDatatanah::route('/{record}/edit'),
        ];
    }
}
