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
use App\Filament\Resources\GcvDatatandaterimaResource\Widgets\gcv_datatandaterimaStats;
class GcvLegalitasResource extends Resource
{
    protected static ?string $model = gcv_legalitas::class;

    protected static ?string $title = "Data Legalitas";
    protected static ?string $navigationGroup = "GCV";
    protected static ?string $pluralLabel = "Data Legalitas";
    protected static ?string $navigationIcon = 'heroicon-o-folder';
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

                // Forms\Components\TextInput::make('no_sertifikat')
                //     ->label('No. Sertifikat')
                //     ->nullable()
                //     ->disabled(fn () => ! (function () {
                //                     /** @var \App\Models\User|null $user */
                //                     $user = Auth::user();
                //                     return $user && $user->hasRole(['admin','Legal officer']);
                //                 })()),

                //                 Forms\Components\TextInput::make('luas_sertifikat')
                //     ->label('Luas Sertifikat')
                //     ->nullable()
                //     ->disabled(fn () => ! (function () {
                //                     /** @var \App\Models\User|null $user */
                //                     $user = Auth::user();
                //                     return $user && $user->hasRole(['admin','Legal officer']);
                //                 })())                            ->columnSpanFull(),

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
                Forms\Components\TextInput::make('nop')
                    ->label('NOP')
                    ->nullable()
                    ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),
                Forms\Components\TextInput::make('nop1')
                                    ->label('NOP Tambahan')
                                    ->nullable()
                                    ->helperText('Jika irisan input data 2X')
                                    ->disabled(fn () => ! (function () {
                                                    /** @var \App\Models\User|null $user */
                                                    $user = Auth::user();
                                                    return $user && $user->hasRole(['admin','Legal officer']);
                                                })()),


            ])->columns(2),

        Step::make('Upload Dokumen')
            ->description('Informasi file dokumen')
            ->schema([
                Forms\Components\FileUpload::make('up_sertifikat')
                    ->label('Sertifikat')
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
                    ->label('PBB/NOP')
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
                    ->label('IMG')
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
            'index' => Pages\ListGcvLegalitas::route('/'),
            'create' => Pages\CreateGcvLegalitas::route('/create'),
            'edit' => Pages\EditGcvLegalitas::route('/{record}/edit'),
        ];
    }
}