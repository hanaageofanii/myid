<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvStokResource\Pages;
use App\Filament\Resources\GcvStokResource\RelationManagers;
use App\Models\gcv_stok;
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

class GcvStokResource extends Resource
{
    protected static ?string $model = gcv_stok::class;

    protected static ?string $title = "Data Bookingan";
    protected static ?string $navigationGroup = "GCV";
    protected static ?string $pluralLabel = "Data Bookingan";
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Stok > Data Booking';
    protected static ?string $pluralModelLabel = 'Data Bookingan';
    protected static ?int $navigationSort = 3;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                Step::make('Data Proyek')->schema([
                Select::make('proyek')
                ->options([
                    'gcv_cira' => 'GCV Cira',
                    'gcv' => 'GCV',
                    'tkr_cira' => 'TKR Cira',
                    'tkr' => 'TKR',
                    'pca1' => 'PCA1',
                ])
                ->label('Proyek')
                ->required()
                ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','KPR Officer']);
                                    })()),
                                    
            Select::make('nama_perusahaan')
                ->options([
                    'grand_cikarang_village' => 'Grand Cikarang Village',
                    'taman_kertamukti_residence' => 'Taman Kertamukti Residence',
                    'pesona_cengkong_asri_1' => 'Pesona Cengkong Asri 1',
                ])
                ->label('Nama Perusahaan')
                ->required()
                ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','KPR Officer']);
                                    })()),
                                    
            Select::make('kavling')
                ->options([
                    'standar' => 'Standar',
                    'khusus' => 'Khusus',
                    'hook' => 'Hook',
                    'komersil' => 'Komersil',
                    'tanah_lebih' => 'Tanah Lebih',
                    'kios' => 'Kios'
                ])
                ->label('Kavling')
                ->required()
                ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','KPR Officer']);
                                    })()),
                                    
            Select::make('siteplan')
                ->label('Blok')
                ->options(
                    gcvDataSiteplan::where('terbangun', 1)->pluck('siteplan', 'siteplan')->toArray()
                )
                ->searchable()
                ->required()
                ->reactive()
                ->unique(ignoreRecord: true)
                ->afterStateUpdated(function ($state, $set) {
                    $audit = gcvDataSiteplan::where('siteplan', $state)->first();

                    if ($audit) {
                        $set('type', $audit->type);
                        $set('luas_tanah', $audit->luas);
                    }
                })
                ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','KPR Officer']);
                                    })()),

            TextInput::make('type')
                ->label('Type')
                ->required()
                ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','KPR Officer']);
                                    })()),

            TextInput::make('luas_tanah')
                ->numeric()
                ->label('Luas Tanah')
                ->required()
                ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','KPR Officer']);
                                    })()),        
                                ]),

        Step::make('Status Bookingan')->schema([
            Select::make('status')
                ->options(['booking' => 'Booking'])
                ->label('Status')
                ->afterStateUpdated(function ($state, $set, $record) {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        if (is_null($state) && $user && $user->hasRole(['Direksi', 'Super admin','admin'])) {
                            $set('tanggal_booking', null);
                            $set('nama_konsumen', null);
                            $set('agent', null);

                            $record->update([
                                'tanggal_booking' => null,
                                'nama_konsumen' => null,
                                'agent' => null
                            ]);
                        }
                    })
                ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

            DatePicker::make('tanggal_booking')
                ->label('Tanggal Booking')
                ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),
                    
            TextInput::make('nama_konsumen')
                ->label('Nama Konsumen')
                ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

            TextInput::make('agent')
                ->label('Agent')
                ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),        ]),

        Step::make('Legalitas')->schema([
            Select::make('status_sertifikat')
                ->options([
                    'pecah' => 'SUDAH PECAH',
                    'belum' => 'BELUM PECAH',
                ])
                ->label('Status Sertifikat')
                ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),
            Select::make('status_pembayaran')
                ->options([
                    'cash' => 'CASH',
                    'kpr' => 'KPR',
                    'cash_bertahap' => 'CASH BERTAHAP',
                    'promo' => 'PROMO',
                ])
                ->label('Status Pembayaran')
                ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),
                ]),

        Step::make('Status KPR')->schema([
            Select::make('kpr_status')
                ->options([
                    'sp3k' => 'SP3K',
                    'akad' => 'Akad',
                    'batal' => 'Batal',
                ])
                ->afterStateUpdated(function ($state, $set, $get, $record) {
                        if ($record && $record->siteplan) {
                            $audit = gcv_datatandaterima::where('siteplan', $record->siteplan)->first();

                            if ($audit) {
                                $audit->update([
                                    'status' => $state === 'akad' ? 'akad' : null,
                                ]);
                            }
                        }
                    })
                    ->afterStateUpdated(function ($state, $set, $record) {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        if (is_null($state) && $user && $user->hasRole(['Direksi', 'Super admin','admin','KPR Stok'])) {
                            $set('tanggal_akad', null);


                            $record->update([
                                'tanggal_akad' => null,
                            ]);
                        }
                    })
                ->label('KPR Status')
                ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),
                    
            DatePicker::make('tanggal_akad')
                ->label('Tanggal Akad')
                ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

            Textarea::make('ket')
                ->label('Keterangan')
                ->nullable()
                ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),       
                ]),
    ])
    ->columnSpanFull()
]
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListGcvStoks::route('/'),
            'create' => Pages\CreateGcvStok::route('/create'),
            'edit' => Pages\EditGcvStok::route('/{record}/edit'),
        ];
    }
}