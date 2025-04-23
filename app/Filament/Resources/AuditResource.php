<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditResource\Pages;
use App\Filament\Resources\AuditResource\RelationManagers;
use App\Models\Audit;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\AuditResource\Widgets\AuditStats;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;




class AuditResource extends Resource
{
    protected static ?string $model = Audit::class;

    protected static ?string $title = "Audit GCV";
    protected static ?string $navigationGroup = "Legal";
    protected static ?string $pluralLabel = "Audit GCV";
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Audit GCV';
    protected static ?string $pluralModelLabel = 'Daftar Audit GCV';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('siteplan')
            ->required()
            ->label('Site Plan')
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Legal officer']);
            })())
            ->unique(ignoreRecord: true),

            
            TextInput::make('type')
                ->label('Type')
                ->required()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal officer']);
                })()),

            Toggle::make('terbangun')
                ->label('Terbangun')
                ->required()
                ->default(false)
                ->onColor('success')
                ->offColor('danger')
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal officer']);
                })()),

            Select::make('status')
                ->label('Status')
                ->required()
                ->options([
                    'akad' => 'Akad',
                ])
                ->required()->nullable()->native(false)
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal officer']);
                })()),

            Fieldset::make('Sertifikat')
                ->schema([
                    TextInput::make('kode1')
                    ->label('Kode 1')
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('luas1')
                    ->label('Luas 1 (m²)')
                    ->required()
                    ->numeric()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('kode2')
                    ->label('Kode 2')
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('luas2')
                    ->label('Luas 2 (m²)')
                    ->numeric()
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),
                    
                    TextInput::make('kode3')
                    ->label('Kode 3')
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('luas3')
                    ->label('Luas 3 (m²)')
                    ->numeric()
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('kode4')
                    ->label('Kode 4')
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('luas4')
                    ->label('Luas 4 (m²)')
                    ->numeric()
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('tanda_terima_sertifikat')
                    ->label('Tanda Terima Sertifikat')
                    ->columnSpanFull()
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),
                ])
                ->columns(4),

            Fieldset::make('Berkas Lainnya')
                ->schema([
                    TextInput::make('nop_pbb_pecahan')
                    ->label('NOP / PBB Pecahan')
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('tanda_terima_nop')
                    ->label('Tanda Terima NOP')
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('imb_pbg')
                    ->label('IMB / PBG')
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('tanda_terima_imb_pbg')
                    ->label('Tanda Terima IMB/PBG')
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    Textarea::make('tanda_terima_tambahan')
                    ->label('Tanda Terima Tambahan')
                    ->required()
                    ->rows(3)->columnSpanFull()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),
                ])
                ->columns(2),
            
                Fieldset::make('Upload Berkas')
                ->schema([
                    FileUpload::make('up_sertifikat')
                        ->disk('public')
                        ->multiple()
                        ->nullable()
                        ->required()
                        ->label('Upload Sertifikat')
                        ->downloadable()
                        ->previewable(false)
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })()),
                    
                    FileUpload::make('up_nop')
                        ->disk('public')
                        ->nullable()
                        ->multiple()
                        ->required()
                        ->label('Upload NOP')
                        ->downloadable()
                        ->previewable(false)
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })()),

                    FileUpload::make('up_imb_pbg')
                        ->disk('public')
                        ->nullable()
                        ->multiple()
                        ->required()
                        ->label('Upload IMB/PBG')
                        ->downloadable()
                        ->previewable(false)
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })()),
                    
                    FileUpload::make('up_tambahan_lainnya')
                        ->disk('public')
                        ->nullable()
                        ->multiple()
                        ->required()
                        ->label('Upload Tambahan Lainnya')
                        ->downloadable()
                        ->previewable(false)
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })()),
                    
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('siteplan')->label('Site Plan')->searchable(),
            TextColumn::make('type')->label('Type')->searchable(),
            BooleanColumn::make('terbangun')->label('Terbangun')->searchable(),
            TextColumn::make('status')
            ->label('Status')
            ->badge()
            ->searchable()
            ->formatStateUsing(fn ($state) => match ($state) {
                    'akad' => 'Akad',
                    default => $state,
                }),

            TextColumn::make('tanda_terima_sertifikat')->label('Tanda Terima Sertifikat')->searchable(),
            TextColumn::make('kode1')->label('1')->searchable(),
            TextColumn::make('luas1')->label('Luas (m²)')->searchable(),
            TextColumn::make('kode2')->label('2')->searchable(),
            TextColumn::make('luas2')->label('Luas (m²)')->searchable(),
            TextColumn::make('kode3')->label('3')->searchable(),
            TextColumn::make('luas3')->label('Luas (m²)')->searchable(),
            TextColumn::make('kode4')->label('4')->searchable(),
            TextColumn::make('luas4')->label('Luas (m²)')->searchable(),
            TextColumn::make('nop_pbb_pecahan')->label('NOP / PBB Pecahan')->limit(20)->searchable(),
            TextColumn::make('tanda_terima_nop')->label('Tanda Terima NOP')->limit(20)->searchable(),
            TextColumn::make('imb_pbg')->label('IMB / PBG')->limit(20)->searchable(),
            TextColumn::make('tanda_terima_imb_pbg')->label('Tanda Terima IMB/PBG')->limit(20)->searchable(),
            TextColumn::make('tanda_terima_tambahan')->label('Tanda Terima Tambahan')->limit(50)->searchable(),

            
            TextColumn::make('up_sertifikat')
                ->label('Upload Sertifikat')
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
                ->label('Upload NOP')
                ->formatStateUsing(function ($record) {
                    if (!$record->up_nop) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->up_nop) ? $record->up_nop : json_decode($record->up_nop, true);

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

            TextColumn::make('up_imb_pbg')
                ->label('Upload IMB/PBG')
                ->formatStateUsing(function ($record) {
                    if (!$record->up_imb_pbg) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->up_imb_pbg) ? $record->up_imb_pbg : json_decode($record->up_imb_pbg, true);

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
                ->label('Upload Tambahan Lainnya')
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
        ])
            ->defaultSort('siteplan', 'asc')
            ->headerActions([
                Action::make('count')
                    ->label(fn ($livewire): string => 'Total: ' . $livewire->getFilteredTableQuery()->count())
                    ->disabled(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
                ->label('Data yang dihapus')
                ->native(false),

                Filter::make('status')
                    ->label('Status')
                    ->form([
                        Select::make('status')
                            ->options([
                                'akad' => 'Akad',
                            ])
                            ->nullable()
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['status']), fn ($q) =>
                            $q->where('status', $data['status'])
                        )
                    ),
            
                Filter::make('terbangun') 
                    ->label('Terbangun')
                    ->form([
                        Select::make('terbangun')
                            ->options([
                                '1' => 'Sudah Terbangun',
                                '0' => 'Belum Terbangun',
                            ])
                            ->nullable()
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['terbangun']), fn ($q) =>
                            $q->where('terbangun', $data['terbangun'])
                        )
                    ),
            
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
                                ->title('Data Audit Diperbarui')
                                ->body('Data Audit telah berhasil disimpan.')),                    
                    DeleteAction::make()
                                ->color('danger')
                                ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                                ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok {$record->siteplan}")
                                ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                                ->successNotification(
                                    Notification::make()
                                        ->success()
                                        ->title('Data Audit Dihapus')
                                        ->body('Data Audit telah berhasil dihapus.')),
                    // RestoreAction::make()
                    //     ->label('Pulihkan')
                    //     ->successNotificationTitle('Data berhasil dipulihkan')
                    //     ->successRedirectUrl(route('filament.admin.resources.audits.index')),
                    Tables\Actions\RestoreAction::make()
                    ->color('info')
                    ->label('Kembalikan Data')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Audit')
                            ->body('Data Audit berhasil dikembalikan.')
                    ),
                    Tables\Actions\ForceDeleteAction::make()
                    ->color('primary')
                    ->label('Hapus Permanen')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Audit')
                            ->body('Data Audit berhasil dihapus secara permanen.')
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
                                ->title('Data Audit')
                                ->body('Data Audit berhasil dihapus.'))                        
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),
                
                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle') 
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Audit')
                                ->body('Data Audit berhasil dihapus secara permanen.'))                        ->requiresConfirmation()
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
                                ->title('Data Audit')
                                ->body('Data Audit berhasil dikembalikan.')),
                ]);
                
    }
    
    public static function exportData(Collection $records)
    {
        $csvData = "ID, Site Plan, Type, Terbangun, Status, Tanda Terima Sertifikat, 1, Luas, 2, Luas, 3, Luas, 4, Luas, NOP / PBB Pecahan, Tanda Terima NOP, IMB / PBG, Tanda Terima IMB/PBG, Tanda Terima Tambahan\n";
    
        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->siteplan}, {$record->type}, {$record->terbangun}, {$record->status}, {$record->tanda_terima_sertifikat}, {$record->kode1}, {$record->luas1}, {$record->kode2}, {$record->luas2}, {$record->kode3}, {$record->luas3}, {$record->kode4}, {$record->luas4}, {$record->nop_pbb_pecahan}, {$record->tanda_terima_nop}, {$record->imb_pbg}, {$record->tanda_terima_imb_pbg}, {$record->tanda_terima_tambahan}\n";
        }
    
        return response()->streamDownload(fn () => print($csvData), 'Audit.csv');
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
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            AuditStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAudits::route('/'),
            'create' => Pages\CreateAudit::route('/create'),
            'edit' => Pages\EditAudit::route('/{record}/edit'),
        ];
    }

}