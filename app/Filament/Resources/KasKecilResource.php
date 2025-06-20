<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KasKecilResource\Pages;
use App\Filament\Resources\KasKecilResource\RelationManagers;
use App\Models\kas_kecil;
use App\Models\KasKecil;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\BukuRekonsil;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Models\Rekening;
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
use Illuminate\Support\Facades\Auth;

class KasKecilResource extends Resource
{
    protected static ?string $model = kas_kecil::class;

protected static ?string $navigationGroup = "Kasir";
    protected static ?string $pluralLabel = "Kas Kecil";
    protected static ?string $navigationLabel = "Kas Kecil";
    protected static ?string $pluralModelLabel = 'Daftar Kecil';
    protected static ?string $navigationIcon = 'heroicon-o-plus';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make()
                ->schema([
                    Select::make('nama_perusahaan')
                    ->label('Nama Perusahaan')
                    ->options([
                        'langgeng_pertiwi_development' => 'PT. Langgeng Pertiwi Development',
                        'agung_purnama_bakti' => 'PT. Agung Purnama Bakti',
                        'purnama_karya_bersama' => 'PT. Purnama Karya Bersama',
                    ])
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('bank', null)) // reset bank saat perusahaan berubah
                    ->required()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Kasir 1','Kasir 2']);
                })()),

                    DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 2','Kasir 1']);
                    })())
                    ->required(),

                     TextArea::make('deskripsi')
                    ->label(' Deskripsi')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 2','Kasir 1']);
                    })())
                    ->required(),

                    TextInput::make('jumlah_uang')
                        ->label('Jumlah Uang')
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (callable $get, callable $set) {
                            $perusahaan = $get('nama_perusahaan');
                            $tipe = $get('tipe');
                            $jumlahUang = (int) $get('jumlah_uang');

                            if (! $perusahaan || ! $tipe || $jumlahUang === null) {
                                return;
                            }

                            $saldoSebelumnya = \App\Models\kas_kecil::where('nama_perusahaan', $perusahaan)
                                ->selectRaw("SUM(CASE WHEN tipe = 'debit' THEN jumlah_uang ELSE -jumlah_uang END) as total")
                                ->value('total') ?? 0;

                            // Hitung saldo baru
                            $saldoBaru = $tipe === 'debit'
                                ? $saldoSebelumnya + $jumlahUang
                                : $saldoSebelumnya - $jumlahUang;

                            $set('saldo', $saldoBaru);
                        })
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 2','Kasir 1']);
                        })()),

                        Select::make('tipe')
                        ->label('Tipe')
                        ->options([
                            'debit' => 'Debit',
                            'kredit' => 'Kredit',
                        ])
                        ->reactive()
                        ->afterStateUpdated(function (callable $get, callable $set) {
                            $perusahaan = $get('nama_perusahaan');
                            $tipe = $get('tipe');
                            $jumlahUang = (int) $get('jumlah_uang');

                            if (! $perusahaan || ! $tipe || $jumlahUang === null) {
                                return;
                            }

                            // Hitung total saldo perusahaan
                            $saldoSebelumnya = \App\Models\kas_kecil::where('nama_perusahaan', $perusahaan)
                                ->selectRaw("SUM(CASE WHEN tipe = 'debit' THEN jumlah_uang ELSE -jumlah_uang END) as total")
                                ->value('total') ?? 0;

                            $saldoBaru = $tipe === 'debit'
                                ? $saldoSebelumnya + $jumlahUang
                                : $saldoSebelumnya - $jumlahUang;

                            $set('saldo', $saldoBaru);
                        })
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 2','Kasir 1']);
                        })()),


                    TextInput::make('saldo')
                    ->label('Saldo')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 2','Kasir 1']);
                    })()),

                    TextInput::make('catatan')
                    ->label('Catatan')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 2','Kasir 1']);
                    })()),

                    Forms\Components\FileUpload::make('bukti')
                    ->disk('public')
                    ->nullable()
                    ->multiple()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 1', 'Kasir 2']);
                    })())
                    ->label('Bukti - Bukti')
                    ->downloadable()
                    ->previewable(false),
                ])
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
            'index' => Pages\ListKasKecils::route('/'),
            'create' => Pages\CreateKasKecil::route('/create'),
            'edit' => Pages\EditKasKecil::route('/{record}/edit'),
        ];
    }
}
