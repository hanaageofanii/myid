<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvPencairanDajamResource\Pages;
use App\Filament\Resources\GcvPencairanDajamResource\RelationManagers;
use App\Models\gcv_pencairan_dajam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\gcv_pencairan_akad;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use App\Models\gcv_stok;
use App\Models\gcv_kpr;
use App\Models\gcvDataSiteplan;
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
use Illuminate\Validation\Rule;
use App\Models\gcv_datatandaterima;
use Filament\Forms\Components\Repeater;
use App\Filament\Resources\GcvLegalitasResource\Widgets\gcv_legalitasStats;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\ForceDeleteAction;
use Carbon\Carbon;

class GcvPencairanDajamResource extends Resource
{
    protected static ?string $model = gcv_pencairan_dajam::class;

    protected static ?string $title = "Data Pencairan Dajam";
    protected static ?string $navigationGroup = "GCV";
    protected static ?string $pluralLabel = "Data Pencairan Dajam";
    protected static ?string $navigationLabel = 'Keuangan > Pencairan Dajam';
    protected static ?string $pluralModelLabel = 'Data Pencairan Dajam';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 Wizard::make([
        Step::make('Data Konsumen')
            ->description('Informasi siteplan, bank, dan konsumen')
            ->schema([
                Section::make('Informasi Siteplan')
                    ->columns(2)
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
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })()),

                        Select::make('siteplan')
                            ->label('Blok')
                            ->options(function (callable $get) {
                                $selectedKavling = $get('kavling');
                                if (! $selectedKavling) {
                                    return [];
                                }

                                return GcvDataSiteplan::where('kavling', $selectedKavling)
                                    ->pluck('siteplan', 'siteplan')
                                    ->toArray();
                            })
                            ->searchable()
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                        ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $kprData = gcv_kpr::where('siteplan', $state)->first();
                                $pencairanAkad = gcv_pencairan_akad::where('siteplan', $state)->first();
                                // $dajamData = dajam::where('siteplan', $state)->first();
                                // $pengDajam = pengajuan_dajam_pca::where('siteplan', $state)->first();

                                $set('bank', $kprData?->bank);
                                $set('nama_konsumen', $kprData?->nama_konsumen);
                                $set('max_kpr', $kprData?->maksimal_kpr);
                                $set('no_debitur', $pencairanAkad?->no_debitur);
                                // $set('pembukuan', $dajamData?->pembukuan);
                                // $set('no_debitur', $dajamData?->no_debitur);
                                // $set('nama_dajam', $pengDajam?->nama_dajam);
                            }),
                    ]),
                Section::make('Identitas Konsumen')
                    ->columns(2)
                    ->schema([
                        Select::make('bank')
                            ->options([
                                'btn_cikarang' => 'BTN Cikarang',
                                'btn_bekasi' => 'BTN Bekasi',
                                'btn_karawang' => 'BTN Karawang',
                                'bjb_syariah' => 'BJB Syariah',
                                'bjb_jababeka' => 'BJB Jababeka',
                                'btn_syariah' => 'BTN Syariah',
                                'brii_bekasi' => 'BRI Bekasi',
                            ])
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                            ->required()
                            ->label('Bank'),
                        TextInput::make('nama_konsumen')
                            ->label('Nama Konsumen')
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                        ->reactive(),
                        TextInput::make('no_debitur')
                            ->label('No. Debitur')
                            ->columnSpanFull()
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                        ->reactive(),
                    ]),
            ]),

        Step::make('Data Pencairan')
            ->description('Informasi pencairan & nilai dajam')
            ->schema([
            Section::make('Nilai dan Selisih Dajam')
                    ->columns(2)
                    ->schema([
                Select::make('nama_dajam')
                            ->options([
                                'sertifikat' => 'Sertifikat',
                                'imb' => 'IMB',
                                'jkk' => 'JKK',
                                'bestek' => 'Bestek',
                                'pph' => 'PPH',
                                'bphtb' => 'BPHTB',
                            ])
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                        ->label('Nama Dajam'),

                        TextInput::make('nilai_dajam')
                            ->label('Nilai Dajam')
                            ->live()
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                        ->reactive()
                            ->prefix('Rp')
                            ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                                $set('selisih_dajam', max(0, ($state ?? 0) - ($get('nilai_pencairan') ?? 0)))
                            ),
                        TextInput::make('nilai_pencairan')
                            ->label('Nilai Pencairan')
                            ->live()
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                        ->reactive()
                            ->prefix('Rp')
                            ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                                $set('selisih_dajam', max(0, ($get('nilai_dajam') ?? 0) - ($state ?? 0)))
                            ),
                        TextInput::make('selisih_dajam')
                            ->label('Selisih Dajam')
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())                            ->prefix('Rp'),
                        DatePicker::make('tanggal_pencairan')
                            ->label('Tanggal Pencairan')
                            ->columnSpanFull()
                            ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->translatedFormat('d F Y'))
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                    ]),
            ]),

        Step::make('Upload Dokumen')
            ->description('Unggah file pendukung')
            ->schema([
                Section::make('Dokumen Pendukung')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('up_rekening_koran')
                            ->label('Upload Rekening Koran')
                            ->disk('public')
                            ->nullable()
                            ->multiple()
                            ->downloadable()
                            ->previewable(false)
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })()),
                        FileUpload::make('up_lainnya')
                            ->label('Upload Dokumen Lainnya')
                            ->disk('public')
                            ->nullable()
                            ->multiple()
                            ->downloadable()
                            ->previewable(false)
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })()),
                    ]),
            ]),
    ])
    ->columnSpanFull()
]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kavling')
                ->label('Kavling')
                ->formatStateUsing(fn(string $state): string => match ($state){
                    'standar' => 'Standar',
                    'khusus' => 'Khusus',
                    'hook' => 'Hook',
                    'komersil' => 'Komersil',
                    'tanah_lebih' => 'Tanah Lebih',
                    'kios' => 'Kios',
                    default => $state,
                })->searchable(),

                TextColumn::make('siteplan')
                ->sortable()
                ->searchabale()
                ->label('Blok'),

                TextColumn::make('bank')
                ->formatStateUsing(fn (string $state): string =>match($state){
                    'btn_cikarang' => 'BTN Cikarang',
                    'btn_bekasi' => 'BTN Bekasi',
                    'btn_karawang' => 'BTN Karawang',
                    'bjb_syariah' => 'BJB Syariah',
                    'bjb_jababeka' => 'BJB Jababeka',
                    'btn_syariah' => 'BTN Syariah',
                    'brii_bekasi' => 'BRI Bekasi',
                    default => ucfirst($state)
                })
                ->sortbale()
                ->searchable()
                ->label('Bank'),

                TextColumn::make('nama_konsumen')
                ->searchable()
                ->label('Nama Konsumen')
                ->sortable(),

                TextColumn::make('no_debitur')
                ->serachable()
                ->sortable()
                ->label('No. Debitur'),

                TextColumn::make('nama_dajam')
                ->formatStateUsing(fn(string $state): string => match ($state){
                    'sertifikat' => 'Sertifikat',
                    'imb' => 'IMB',
                    'jkk' => 'JKK',
                    'bestek' => 'Bestek',
                    'pph' => 'PPH',
                    'bphtb' => 'BPHTB',
                    default => ucfirst($state),
                })
                ->sortable()
                ->searchable()
                ->label('Nama Dajam'),

                TextColumn::make('nilai_dajam')
                ->sortable()
                ->searchable()
                ->label('Nilai Dajam')
                ->formatStateUsing(fn($state)=>'Rp ' . number_format((float)$state, 0, ',', '.')),

                TextColumn::make('tanggal_pencairan')
                ->searchable()
                ->sortable()
                ->label('Tanggal Pencairan')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),

                TextColumn::make('nilai_pencairan')
                ->sortable()
                ->searchable()
                ->label('Nilai Pencairan')
                ->formatStateUsing(fn($state)=>'Rp ' . number_format((float)$state, 0, ',', '.')),

                TextColumn::make('selisih_dajam')
                ->sortable()
                ->searchable()
                ->label('Selisih Dajam')
                ->formatStateUsing(fn($state)=>'Rp ' . number_format((float)$state, 0, ',', '.')),

                TextColumn::make('up_rekening_koran')
                ->label('File Rekening Koran')
                ->formatStateUsing(function ($record){
                    if (!$record->up_rekening_koran){
                        return 'Tidak Ada Dokumen';
                    }
                })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGcvPencairanDajams::route('/'),
            'create' => Pages\CreateGcvPencairanDajam::route('/create'),
            'edit' => Pages\EditGcvPencairanDajam::route('/{record}/edit'),
        ];
    }
}
