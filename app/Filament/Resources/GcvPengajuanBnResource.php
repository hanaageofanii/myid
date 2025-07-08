<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvPengajuanBnResource\Pages;
use App\Filament\Resources\GcvPengajuanBnResource\RelationManagers;
use App\Models\gcv_pengajuan_bn;
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
use App\Models\gcv_validasi_pph;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\ForceDeleteAction;
use Carbon\Carbon;

class GcvPengajuanBnResource extends Resource
{
    protected static ?string $model = gcv_pengajuan_bn::class;

    protected static ?string $title = "Data Pengajuan BN";
    protected static ?string $navigationGroup = "GCV";
    protected static ?string $pluralLabel = "Data Pengajuan BN";
    protected static ?string $navigationLabel = 'Legal > Pengajuan BN';
    protected static ?string $pluralModelLabel = 'Data Pengajuan BN';
    protected static ?int $navigationSort = 17;
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Data Konsumen')
                    ->columns(2)
                    ->description('Informasi Data Konsumen')
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

                        Select::make('siteplan')
                            ->label('Blok')
                            ->options(function (callable $get) {
                                $selectedKavling = $get('kavling');
                                if (! $selectedKavling) {
                                    return [];
                                }

                                return gcv_kpr::where('jenis_unit', $selectedKavling)
                                    ->where('status_akad', 'akad')
                                    ->pluck('siteplan', 'siteplan')
                                    ->toArray();
                            })
                            ->searchable()
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })())
                        ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $kprData = gcv_kpr::where('siteplan', $state)->first();
                                $validasipph = gcv_validasi_pph::where('siteplan', $state)->first();
                                $set('nama_konsumen', $kprData?->nama_konsumen);
                                $set('luas', $kprData?->luas);
                                $set('nama_notaris', $validasipph?->nama_notaris);
                                $set('nop', $validasipph?->nop);
                                $set('harga_jual', $kprData?->harga);
                            }),

                        TextInput::make('nama_konsumen')
                        ->label('Nama Konsumen')
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })()),

                        TextInput::make('luas')
                        ->label('Luas')
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })),
                     ]),
                        Step::make('Informasi Harga')
                        ->columns(2)
                        ->description('Informasi Harga Unit KPR')
                        ->schema([
                            TextInput::make('harga_jual')
                            ->label('Harga Jual')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })),

                            DatePicker::make('tanggal_lunas')
                            ->label('Tanggal Lunas')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })),

                            TextInput::make('nop')
                            ->label('NOP')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })),

                            TextInput::make('nama_notaris')
                            ->label('Nama Notaris')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            }))
                        ]),

                        Step::make('Informasi Lanjutan')
                    ])->columnSpanFull(),
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
            'index' => Pages\ListGcvPengajuanBns::route('/'),
            'create' => Pages\CreateGcvPengajuanBn::route('/create'),
            'edit' => Pages\EditGcvPengajuanBn::route('/{record}/edit'),
        ];
    }
}
