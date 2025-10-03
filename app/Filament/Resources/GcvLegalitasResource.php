<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvLegalitasResource\Pages;
use App\Filament\Resources\GcvLegalitasResource\RelationManagers;
use App\Models\gcv_legalitas;
use App\Models\GcvLegalitas;
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
use App\Models\gcv_datatandaterima;
use Filament\Forms\Components\Repeater;
use App\Filament\Resources\GcvLegalitasResource\Widgets\gcv_legalitasStats;
class GcvLegalitasResource extends Resource
{
    protected static ?string $model = gcv_legalitas::class;

    protected static ?string $title = "Data Legalitas";
    protected static ?string $navigationGroup = "GCV";
    protected static ?string $pluralLabel = "Data Legalitas";
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationLabel = 'Legal > Data Legalitas';
    protected static ?string $pluralModelLabel = 'Data Legalitas';
    protected static ?int $navigationSort = 3;


    public static function form(Form $form): Form
    {
        return $form->schema([
            Wizard::make([
            Step::make('Data Kavling')
            ->description('Informasi Data kavling')
            ->schema([
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
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $selectedKavling = $get('kavling');

                    if (!$selectedKavling) {
                        return;
                    }

                    $data = gcv_datatandaterima::where('siteplan', $state)
                        ->where('kavling', $selectedKavling)
                        ->first();

                    if ($data) {
                        $sertifikatList = [];

                        for ($i = 1; $i <= 4; $i++) {
                            $luas = $data->{'luas' . $i} ?? null;
                            $kode = $data->{'kode' . $i} ?? null;

                            if (!empty($luas) || !empty($kode)) {
                                $sertifikatList[] = [
                                    'luas' => $luas,
                                    'kode' => $kode,
                                ];
                            }
                        }

                        $set('sertifikat_list', $sertifikatList);

                        $nopList = collect(explode(',', $data->nop_pbb_pecahan))
                            ->map(fn ($item) => ['nop' => trim($item)])
                            ->toArray();

                        $set('nop', $nopList);

                        $set('imb_pbg', $data->imb_pbg ?? null);
                    }
                })
    ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),

                Forms\Components\TextInput::make('id_rumah')
                    ->label('No. ID Rumah')
                    ->nullable()
                    ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })())->columnSpanfull(),
            ]),

        Step::make('Data Sertifikat')
        ->description('Informasi data sertifikat')
            ->schema([
                Forms\Components\Select::make('status_sertifikat')
                    ->label('Status Sertifikat')
                    ->options([
                        'induk' => 'Induk',
                        'pecahan' => 'Pecahan',
                    ])
                    ->nullable()
                    ->columnSpanFull()
                    ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),

                Repeater::make('sertifikat_list')
    ->label('Data Sertifikat')
    ->schema([
        TextInput::make('luas')
            ->label('Luas Sertifikat')
            ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),
        TextInput::make('kode')
            ->label('No. Sertifikat')
           ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),
    ])
    ->columns(2)
    ->columnSpanFull()
    ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),
            ]),

        Step::make('Legal Dokumen')
            ->description('Informasi dokumen')
            ->schema([
                Forms\Components\TextInput::make('imb_pbg')
                    ->label('IMB/PBG')
                    ->nullable()
                    ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),
                Forms\Components\TextInput::make('nib')
                                                    ->label('NIB')
                                                    ->nullable()
                                                    ->disabled(fn () => ! (function () {
                                                                    /** @var \App\Models\User|null $user */
                                                                    $user = Auth::user();
                                                                    return $user && $user->hasRole(['admin','Legal officer']);
                                                                })()),
                 Forms\Components\Repeater::make('nop')
    ->label('Daftar NOP')
    ->schema([
        Forms\Components\TextInput::make('nop')
            ->label('NOP')
            ->nullable(),
    ])
    ->addActionLabel('Tambah NOP')
    ->defaultItems(1)
    ->columnSpanFull()
    ->reorderable()
    ->columns(1)
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


        Step::make('Upload Dokumen')
            ->description('Informasi file dokumen')
            ->schema([
                Forms\Components\FileUpload::make('up_sertifikat')
                    ->label('Upload Sertifikat')
                    ->disk('public')
                    ->nullable()
                    ->multiple()
                    ->downloadable()
                    ->previewable(false)
    ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),

                Forms\Components\FileUpload::make('up_pbb')
                    ->label('Upload PBB/NOP')
                    ->disk('public')
                    ->nullable()
                    ->multiple()
                    ->downloadable()
                    ->previewable(false)
    ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),

                Forms\Components\FileUpload::make('up_img')
                    ->label('Upload IMG')
                    ->disk('public')
                    ->nullable()
                    ->multiple()
                    ->downloadable()
                    ->previewable(false)
    ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })())->columnSpanFull(),
                ])
            ]) ->columnSpanFull()->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('siteplan')->sortable()->searchable()->label('Blok'),
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
                Tables\Columns\TextColumn::make('id_rumah')->sortable()->searchable()->label('No. ID Rumah'),
                Tables\Columns\TextColumn::make('status_sertifikat')
                ->sortable()
                ->searchable()
                ->label('Status Sertifikat')
                ->formatStateUsing(fn ($state) => match ($state) {
                        'induk' => 'Induk',
                        'pecahan' => 'Pecahan',
                default => $state,
                }),
                Tables\Columns\TextColumn::make('nib')->sortable()->searchable()->label('NIB'),
                Tables\Columns\TextColumn::make('sertifikat_list')
                    ->label('Data Sertifikat')
                    ->formatStateUsing(function ($state) {
                        if (is_string($state) && str_contains($state, '{') && !str_starts_with($state, '[')) {
                            $state = '[' . $state . ']';
                        }

                        $state = is_string($state) ? json_decode($state, true) : $state;

                        if (!is_array($state) || empty($state)) {
                            return '-';
                        }

                        return collect($state)
                            ->map(fn ($item) => ($item['kode'] ?? '-') . ' (' . ($item['luas'] ?? '-') . ' m²)')
                            ->implode(', ');
                    })
                    ->wrap()
                    ->limit(999),
                Tables\Columns\TextColumn::make('nop')
    ->label('NOP')
    ->formatStateUsing(function ($state) {
        if (is_string($state) && str_contains($state, '{') && !str_starts_with($state, '[')) {
            $state = '[' . $state . ']';
        }

        $state = is_string($state) ? json_decode($state, true) : $state;

        if (!is_array($state) || empty($state)) {
            return '-';
        }

        return collect($state)
            ->map(fn ($item) => $item['nop'] ?? '-')
            ->implode(', ');
    })
    ->wrap()
    ->limit(999),
                Tables\Columns\TextColumn::make('imb_pbg')->sortable()->searchable()->label('IMB/PBG'),
                Tables\Columns\TextColumn::make('keterangan')->sortable()->searchable()->label('Keterangan'),
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

                TextColumn::make('up_pbb')
                ->label('File PBB')
                ->formatStateUsing(function ($record) {
                    if (!$record->up_pbb) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->up_pbb) ? $record->up_pbb : json_decode($record->up_pbb, true);

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

                TextColumn::make('up_img')
                ->label('File IMG')
                ->formatStateUsing(function ($record) {
                    if (!$record->up_img) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->up_img) ? $record->up_img : json_decode($record->up_img, true);

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
                Filter::make('status_sertifikat')
                    ->label('Status Sertifikat')
                    ->form([
                        Select::make('status_sertifikat')
                            ->options([
                                'induk' => 'Induk',
                                'pecahan' => 'Pecahan',
                            ])
                            ->nullable()
                            ->native(false),
                            ])
                            ->query(fn ($query, $data) =>
                                $query->when(isset($data['kavling']), fn ($q) =>
                                    $q->where('kavling', $data['kavling'])
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
            ->filtersFormMaxHeight('200px')
            ->filtersFormColumns(5)
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
                                ->title('Data Legalitas Diperbarui')
                                ->body('Data Legalitas  telah berhasil disimpan.')),
                    DeleteAction::make()
                                ->color('danger')
                                ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                                ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok {$record->siteplan}")
                                ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                                ->successNotification(
                                    Notification::make()
                                        ->success()
                                        ->title('Data Legalitas Dihapus')
                                        ->body('Data Legalitas telah berhasil dihapus.')),
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
                            ->title('Data Legalitas')
                            ->body('Data Legalitas berhasil dikembalikan.')
                    ),
                    Tables\Actions\ForceDeleteAction::make()
                    ->color('primary')
                    ->label(fn ($record) => "Hapus Permanent {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok Permanent{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus blok secara permanent {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Legalitas')
                            ->body('Data Legalitas  berhasil dihapus secara permanen.')
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
                                ->title('Data Legalitas')
                                ->body('Data Legalitas berhasil dihapus.'))
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),

                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Legalitas')
                                ->body('Data Legalitas berhasil dihapus secara permanen.'))
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
                        ->action(function (Collection $records) {
                            $ids = $records->pluck('id')->toArray();
                            session(['print_records' => $ids]);
                            return redirect(route('datalegalitas.print'));
                        }),
                    
                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Kembalikan Data')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->button()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Legalitas')
                                ->body('Data Legalitas berhasil dikembalikan.')),
                ]);

    }

    public static function exportData(Collection $records)
    {
        $csvData = "ID, Site Plan, Kavling, Id. Rumah, Status Sertifikat, NIB, IMB, NOP, Sertifikat List\n";

        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->siteplan}, $record->kavling}, {$record->id_rumah}, {$record->status_sertifikat}, {$record->nib}, {$record->imb_pbg}, $record->nop}, {$record->sertifikat_list}\n";
        }

        return response()->streamDownload(fn () => print($csvData), 'DataLegalitas.csv');
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
                gcv_legalitasStats::class,
            ];
        }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGcvLegalitas::route('/'),
            'create' => Pages\CreateGcvLegalitas::route('/create'),
            'edit' => Pages\EditGcvLegalitas::route('/{record}/edit'),
        ];
    }
}