<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AjbResource\Pages;
use App\Filament\Resources\AjbResource\RelationManagers;
use App\Models\ajb;
use App\Models\form_kpr;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\PencairanAkad;
use App\Models\form_dp;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
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
use Filament\Tables\Actions\ForceDeleteAction;
use App\Models\FormKpr;
use App\Models\Audit;
use App\Filament\Resources\FormLegalResource;
use App\Models\GCV;
use App\Filament\Resources\KPRStats;
use App\Models\form_legal;
use App\Models\form_pajak;
use Illuminate\Support\Facades\Auth;




class AjbResource extends Resource
{
    protected static ?string $model = Ajb::class;

    protected static ?string $title = "AJB";
    protected static ?string $navigationGroup = "Legal - Pajak";
    protected static ?string $pluralLabel = "AJB GCV";
    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $navigationLabel = 'AJB GCV';
    protected static ?string $pluralModelLabel = 'Daftar AJB GCV';
    public static function form(Form $form): Form
    {
        return $form->schema([
            Fieldset::make('Data Konsumen')
            ->schema([
                Select::make('siteplan')
                ->label('Blok')
                ->options(fn () => form_kpr::pluck('siteplan', 'siteplan')) 
                ->searchable()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $kprData = form_kpr::where('siteplan', $state)->first();
                    if ($kprData) {
                        $set('kavling', $kprData->jenis_unit);
                        $set('nama_konsumen', $kprData->nama_konsumen);
                        $set('nik', $kprData->nik);
                        $set('npwp', $kprData->npwp);
                        $set('alamat', $kprData->alamat);
                }
            

                        $legalData = form_legal::where('siteplan', $state)->first();
                        if ($legalData) {
                            $set('nop', $legalData->nop);
                        }

                        // $pajakData = form_pajak::where('siteplan', $state)->first();
                        // if ($pajakData) {
                        //     $set('nop', $pajakData->nop);
                        // }
                    }),
                    TextInput::make('nop')
                    ->nullable  ()
                    ->label('NOP')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->reactive(),

                    TextInput::make('nama_konsumen')
                    ->nullable()
                    ->label('Nama Konsumen')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->reactive(),

                    TextInput::make('nik')
                    ->nullable()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->label('NIK')
                    ->reactive(),

                    TextInput::make('npwp')
                    ->nullable()
                    ->label('NPWP')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->reactive(),

                    TextArea::make('alamat')
                    ->nullable()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->label('Alamat')
                    ->reactive(),
                ]),

                Fieldset::make('Data AJB')
                ->schema([
                    TextInput::make('suket_validasi')
                    ->nullable()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->label('No. Suket Validasi'),

                    TextInput::make('no_sspd_bptb')
                    ->nullable()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->label('No. SSPD BPHTB'),

                    DatePicker::make('tanggal_sspd_bphtb')
                    ->nullable()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->label('Tanggal SSPD BPHTB'),

                    TextInput::make(   'no_validasi_sspd_bphtb')
                    ->nullable()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->label('No. Validasi SSPD BPHTB'),

                    TextInput::make('notaris')
                    ->nullable()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->label('Notaris'),

                    TextInput::make('no_ajb')
                    ->nullable()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->label('No. AJB'),

                    DatePicker::make('tanggal_ajb')
                    ->nullable()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->label('Tanggal AJB'),

                    TextInput::make('no_bast')
                    ->nullable()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->label('No. Bast'),

                    DatePicker::make(  'tanggal_bast')
                    ->nullable()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->label('Tanggal Bast'),
                ]),
                Fieldset::make('Dokumen')
                ->schema([
                    FileUpload::make('up_validasi_bphtb')
                    ->disk('public')
                    ->nullable()
                    ->multiple()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->label('Upload BPHTB')
                    ->downloadable()
                    ->previewable(false),

                    FileUpload::make('up_bast')
                    ->disk('public')
                    ->nullable()
                    ->multiple()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->label('Upload Bast')
                    ->downloadable()
                    ->previewable(false),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siteplan')->searchable()->label('Blok'),
                TextColumn::make('nop')->searchable()->label('NOP'),
                TextColumn::make('nama_konsumen')->searchable()->label('Nama Konsumen'),
                TextColumn::make('nik')->searchable()->label('NIK'),
                TextColumn::make('npwp')->searchable()->label('NPWP'),
                TextColumn::make('alamat')->searchable()->label('Alamat'),
                TextColumn::make('suket_validasi')->searchable()->label('Suket Validasi'),
                TextColumn::make('no_sspd_bphtb')->searchable()->label('No. SSPD BPHTB'),
                TextColumn::make('tanggal_sspd_bphtb')
                ->searchable()
                ->label('Tanggal SSPD BPHTB')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
                TextColumn::make('no_validasi_sspd_bphtb')->searchable()->label('NO. Validasi SSPD BPHTB'),
                TextColumn::make('notaris')->searchable()->label('Notaris'),
                TextColumn::make('no_ajb')->searchable()->label('NO. AJB'),
                TextColumn::make('tanggal_ajb')
                ->searchable()
                ->label('Tanggal AJB')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
                TextColumn::make('no_bast')->searchable()->label('NO. Bast'),
                TextColumn::make('tanggal_bast')
                ->searchable()
                ->label('Tanggal Bast')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
                
                TextColumn::make('up_bast')
                ->label('Bast')
                ->formatStateUsing(function ($record) {
                    if (!$record->up_bast) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->up_bast) ? $record->up_bast : json_decode($record->up_bast, true);

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

                TextColumn::make('up_bphtb')
                ->label('BPHTB')
                ->formatStateUsing(function ($record) {
                    if (!$record->up_bphtb) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->up_bphtb) ? $record->up_bphtb : json_decode($record->up_bphtb, true);

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
            ])
            ->defaultSort('siteplan', 'asc')
            ->headerActions([
                Action::make('count')
                    ->label(fn ($livewire): string => 'Total: ' . $livewire->getFilteredTableQuery()->count())
                    ->disabled(),
            ])
            ->filters([
                TrashedFilter::make()
                ->label('Data yang dihapus') 
                ->native(false),
                    Filter::make('created_from')
                    ->label('Dari Tanggal')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Dari')
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
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when($data['created_until'] ?? null, fn ($q) =>
                            $q->whereDate('created_at', '<=', $data['created_until'])
                        )
                    ),                
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormMaxHeight('400px')
            ->filtersFormColumns(4)
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
                                ->title('Data AJB Diubah')
                                ->body('Data AJB telah berhasil disimpan.')),                    
                        DeleteAction::make()
                        ->color('danger')
                        ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                        ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok{$record->siteplan}")
                        ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data AJB Dihapus')
                                ->body('Data AJB telah berhasil dihapus.')),                            
                    // RestoreAction::make()
                    //     ->label('Pulihkan')
                    //     ->successNotificationTitle('Data berhasil dipulihkan')
                    //     ->successRedirectUrl(route('filament.admin.resources.audits.index')),
                    RestoreAction::make()
                    ->color('info')
                    ->label(fn ($record) => "Kembalikan {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Kembalikan Blok{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengembalikan blok {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data AJB')
                            ->body('Data AJB berhasil dikembalikan.')
                    ),
                    ForceDeleteAction::make()
                    ->color('primary')
                    ->label(fn ($record) => "Hapus Permanent {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok Permanent{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus blok secara permanent {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data AJB')
                            ->body('Data AJB berhasil dihapus secara permanen.')
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
                                ->title('Data AJB')
                                ->body('Data AJB berhasil dihapus.'))                        
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),
                
                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle') 
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data AJB')
                                ->body('Data AJB berhasil dihapus secara permanen.'))                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->forceDelete()),
                
                    BulkAction::make('export')
                        ->label('Download Data')
                        ->icon('heroicon-o-arrow-down-tray') 
                        ->color('info')
                        ->action(fn (Collection $records) => static::exportData($records)),
                
                    RestoreBulkAction::make()
                        ->label('Kembalikan Data')
                        ->icon('heroicon-o-arrow-path') 
                        ->color('success')
                        ->button()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data AJB')
                                ->body('Data AJB berhasil dikembalikan.')),
                ]);
                
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function exportData(Collection $records)
    {
        $csvData = "ID, Blok, NOP, Nama Konsumen, NIK, NPWP, Alamat, Suket Validasi, NO. SSPD BPHTB, Tanggal SSPD BPHTB, No. Validasi, Notaris, No. AJB, Tanggal AJB, NO. Bast, Tanggal Bast\n";
    
        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->siteplan}, {$record->nop}, {$record->nama_konsumen}, {$record->nik}, {$record->npwp}, {$record->alamat}, {$record->suket_validasi}, {$record->no_sspd_bphtb}, {$record->tanggal_sspd_bphtb}, {$record->no_validasi_sspd_bphtb}, {$record->notaris}, {$record->no_ajb}, {$record->tanggal_ajb}, {$record->no_bast}, {$record->tanggal_bast}\n";
        }
    
        return response()->streamDownload(fn () => print($csvData), 'AJB.csv');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAjbs::route('/'),
            'create' => Pages\CreateAjb::route('/create'),
            'edit' => Pages\EditAjb::route('/{record}/edit'),
        ];
    }
}
