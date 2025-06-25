<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvDatatandaterimaResource\Pages;
use App\Filament\Resources\GcvDatatandaterimaResource\RelationManagers;
use App\Models\gcv_datatandaterima;
use App\Models\gcvDataSiteplan;
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
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Validation\Rule;
use App\Filament\Resources\GcvDatatandaterimaResource\Widgets\gcv_datatandaterimaStats;

class GcvDatatandaterimaResource extends Resource
{
    protected static ?string $model = gcv_datatandaterima::class;

    protected static ?string $title = "Data Tanda Terima";
    protected static ?string $navigationGroup = "GCV";
    protected static ?string $pluralLabel = "Data Tanda Terima";
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Legal > Data Tanda Terima';
    protected static ?string $pluralModelLabel = 'Data Tanda Terima';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
return $form->schema([
    Wizard::make([
        Step::make('Data Umum')
            ->description('Informasi dasar kavling')
            ->schema([
                Section::make('Data Kavling')
                ->columns(3)
                    ->schema([
                        // TextInput::make('siteplan')
                        //     ->label('Site Plan')
                        //     ->required()
                        //     ->unique(ignoreRecord: true)
                        //     ->disabled(fn () => ! (function () {
                        //         /** @var \App\Models\User|null $user */
                        //         $user = Auth::user();
                        //         return $user && $user->hasRole(['admin','Legal officer']);
                        //     })()),
                    Select::make('kavling')
                        ->label('Kavling')
                        ->options([
                            'standar' => 'Standar',
                            'khusus' => 'Khusus',
                            'hook' => 'Hook',
                            'komersil' => 'Komersil',
                            'tanah_lebih' => 'Tanah Lebih',
                            'kios' => 'Kios',
                        ])
                        ->required()
                        ->reactive()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })()),

                    Forms\Components\Select::make('siteplan')
                        ->label('Blok')
                        ->searchable()
                        ->required()
                        ->reactive()
                        ->options(function (callable $get) {
                            $selectedKavling = $get('kavling');
                            if (! $selectedKavling) {
                                return [];
                            }
                            return GcvDataSiteplan::where('kavling', $selectedKavling)
                                ->pluck('siteplan', 'siteplan')
                                ->toArray();
                        })
                        ->afterStateUpdated(function ($state, callable $set) {
                            $data = GcvDataSiteplan::where('siteplan', $state)->first();

                            if ($data) {
                                $set('type', $data->type);
                                $set('luas', $data->luas);
                                $set('terbangun', $data->terbangun);
                            }
                        })
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })()),


                        TextInput::make('type')
                            ->label('Type')
                            ->required()
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                        TextInput::make('luas')
                            ->label('Luas (m²)')
                            ->required()
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                        Toggle::make('terbangun')
                            ->label('Terbangun')
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
                            ->options([
                                'akad' => 'Akad',
                            ])
                            ->nullable()
                            ->native(false)
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                            Select::make('status_bn')
                            ->label('Penyelesaian BN')
                            ->options([
                                'sudah' => 'Sudah Selesai',
                                'belum' => 'Belum Selesai',

                            ])->columnSpanFull()
                            ->nullable()
                            ->native(false)
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                    ]),
            ]),

        Step::make('Sertifikat')
            ->description('Data sertifikat tanah')
            ->schema([
                Fieldset::make('Informasi Sertifikat')
                    ->schema([
                        TextInput::make('kode1')->label('Kode 1'),
                        TextInput::make('luas1')->label('Luas 1 (m²)')->numeric(),
                        TextInput::make('kode2')->label('Kode 2'),
                        TextInput::make('luas2')->label('Luas 2 (m²)')->numeric(),
                        TextInput::make('kode3')->label('Kode 3'),
                        TextInput::make('luas3')->label('Luas 3 (m²)')->numeric(),
                        TextInput::make('kode4')->label('Kode 4'),
                        TextInput::make('luas4')->label('Luas 4 (m²)')->numeric(),
                        TextInput::make('tanda_terima_sertifikat')
                            ->label('Tanda Terima Sertifikat')
                            ->columnSpanFull(),
                    ])
                    ->columns(4)
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),
            ]),

        Step::make('Berkas Lainnya')
            ->description('Data tambahan legalitas')
            ->schema([
                Fieldset::make('Berkas Lain')
                    ->schema([
                        TextInput::make('nop_pbb_pecahan')->label('NOP / PBB Pecahan'),
                        TextInput::make('tanda_terima_nop')->label('Tanda Terima NOP'),
                        TextInput::make('imb_pbg')->label('IMB / PBG'),
                        TextInput::make('tanda_terima_imb_pbg')->label('Tanda Terima IMB/PBG'),
                        Textarea::make('tanda_terima_tambahan')
                            ->label('Tanda Terima Tambahan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextArea::make('keterangan')
                            ->label('Keterangan')
                            ->required()->columnSpanFull()
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),
            ]),

        Step::make('Upload Berkas')
            ->description('Unggah dokumen pendukung')
            ->schema([
                Fieldset::make('Upload Dokumen')
                    ->schema([
                        FileUpload::make('up_sertifikat')
                            ->label('Upload Tanda Terima Sertifikat')
                            ->disk('public')->multiple()->nullable()
                            ->downloadable()->previewable(false),

                        FileUpload::make('up_nop')
                            ->label('Upload Tanda Terima NOP')
                            ->disk('public')->multiple()->nullable()
                            ->downloadable()->previewable(false),

                        FileUpload::make('up_imb_pbg')
                            ->label('Upload Tanda Terima IMB/PBG')
                            ->disk('public')->multiple()->nullable()
                            ->downloadable()->previewable(false),

                        FileUpload::make('up_tambahan_lainnya')
                            ->label('Upload Tanda Terima Lainnya')
                            ->disk('public')->multiple()->nullable()
                            ->downloadable()->previewable(false),
                    ])
                    ->columns(2)
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
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
            TextColumn::make('siteplan')->label('Site Plan')->searchable(),
           TextColumn::make('kavling')->label('Kavling')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'standar' => 'Standar',
                    'khusus' => 'Khusus',
                    'hook' => 'Hook',
                    'komersil' => 'Komersil',
                    'tanah_lebih' => 'Tanah Lebih',
                    'kios' => 'Kios',
                    default => $state,
                })->searchable(),
            TextColumn::make('type')->label('Type')->searchable(),
            TextColumn::make('luas')->label('Luas')->searchable(),
            BooleanColumn::make('terbangun')->label('Terbangun')->searchable(),
            TextColumn::make('status')
            ->label('Status')
            ->badge()
            ->searchable()
            ->formatStateUsing(fn ($state) => match ($state) {
                    'akad' => 'Akad',
                    default => $state,
                }),
            TextColumn::make('status BN')
            ->label('Status BN')
            ->badge()
            ->searchable()
            ->formatStateUsing(fn ($state) => match ($state) {
                    'sudah' => 'Sudah Selesai',
                    'belum' => 'Belum Selesai',
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
            TextColumn::make('keterangan')->label('Keterangan')->searchable(),
            TextColumn::make('up_sertifikat')
                ->label('File Tanda Terima Sertifikat')
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
                ->label('File Tanda Terima NOP')
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
                ->label('File Tanda Terima IMB/PBG')
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
                ->label('File Tanda Terima Lainnya')
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

                Filter::make('kavling')
                    ->label('Kavling')
                    ->form([
                        Select::make('kavling')
                            ->options([
                                'standar' => 'Standar',
                                'khusus' => 'Khusus',
                                'hook' => 'Hook',
                                'komersil' => 'Komersil',
                                'tanah_lebih' => 'Tanah Lebih',
                                'kios' => 'Kios',
                            ])
                            ->nullable()
                            ->native(false),
                            ])
                            ->query(fn ($query, $data) =>
                                $query->when(isset($data['kavling']), fn ($q) =>
                                    $q->where('kavling', $data['kavling'])
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

                Filter::make('status_bn')
                ->form([
                Select::make('status_bn')
                    ->options([
                        'sudah' => 'Sudah Selesai',
                        'belum' => 'Belum Selesai',
                    ])
                    ->nullable()->label('Status BN')
                    ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['status_bn']), fn ($q) =>
                            $q->where('status_bn', $data['status_bn'])
                        )
                    ),

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
                                ->title('Data Tanda Terima Diperbarui')
                                ->body('Data Tanda Terima  telah berhasil disimpan.')),
                    DeleteAction::make()
                                ->color('danger')
                                ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                                ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok {$record->siteplan}")
                                ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                                ->successNotification(
                                    Notification::make()
                                        ->success()
                                        ->title('Data Tanda Terima  Dihapus')
                                        ->body('Data Tanda Terima telah berhasil dihapus.')),
                    // RestoreAction::make()
                    //     ->label('Pulihkan')
                    //     ->successNotificationTitle('Data berhasil dipulihkan')
                    //     ->successRedirectUrl(route('filament.admin.resources.Siteplans.index')),
                    Tables\Actions\RestoreAction::make()
                    ->color('info')
                    ->label(fn ($record) => "Kembalikan {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Kembalikan Blok{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengembalikan blok {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Tanda Terima')
                            ->body('Data Tanda Terima berhasil dikembalikan.')
                    ),
                    Tables\Actions\ForceDeleteAction::make()
                    ->color('primary')
                    ->label(fn ($record) => "Hapus Permanent {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok Permanent{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus blok secara permanent {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Tanda Terima')
                            ->body('Data Tanda Terima  berhasil dihapus secara permanen.')
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
                                ->title('Data Tanda Terima')
                                ->body('Data Tanda Terima berhasil dihapus.'))
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),

                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Tanda Terima')
                                ->body('Data Tanda Terima berhasil dihapus secara permanen.'))
                                ->requiresConfirmation()
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
                                ->title('Data Tanda Terima')
                                ->body('Data Tanda Terima berhasil dikembalikan.')),
                ]);

    }

    public static function exportData(Collection $records)
    {
        $csvData = "ID, Site Plan, Kavling, Type, Luas, Terbangun, Status, Status BN, Tanda Terima Sertifikat, 1, Luas, 2, Luas, 3, Luas, 4, Luas, NOP / PBB Pecahan, Tanda Terima NOP, IMB / PBG, Tanda Terima IMB/PBG, Tanda Terima Tambahan\n";

        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->siteplan}, $record->kavling}, {$record->type}, {$record->luas}, {$record->terbangun}, {$record->status}, $record->status_bn}, {$record->tanda_terima_sertifikat}, {$record->kode1}, {$record->luas1}, {$record->kode2}, {$record->luas2}, {$record->kode3}, {$record->luas3}, {$record->kode4}, {$record->luas4}, {$record->nop_pbb_pecahan}, {$record->tanda_terima_nop}, {$record->imb_pbg}, {$record->tanda_terima_imb_pbg}, {$record->tanda_terima_tambahan}\n";
        }

        return response()->streamDownload(fn () => print($csvData), 'DataTandaTerima.csv');
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
                gcv_datatandaterimaStats::class,
            ];
        }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGcvDatatandaterimas::route('/'),
            'create' => Pages\CreateGcvDatatandaterima::route('/create'),
            'edit' => Pages\EditGcvDatatandaterima::route('/{record}/edit'),
        ];
    }
}