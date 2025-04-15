<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekeningKoranResource\Pages;
use App\Filament\Resources\RekeningKoranResource\RelationManagers;
use App\Models\rekening_koran;
use App\Models\RekeningKoran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Rekonsil;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Models\form_dp;
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

class RekeningKoranResource extends Resource
{
    protected static ?string $model = rekening_koran::class;

    protected static ?string $title = "Input Rekening Koran";

    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = "Kasir";
    protected static ?string $pluralLabel = "Rekening Koran";
    protected static ?string $navigationLabel = "Rekening Koran";
    protected static ?string $pluralModelLabel = 'Daftar Rekening Koran';
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make()
                ->schema([
                    Select::make('no_transaksi')
                    ->label('No. Transaksi')
                    ->options(fn () => rekonsil::pluck('no_transaksi', 'no_transaksi')) 
                    ->searchable()
                    ->reactive(),
                    // ->afterStateUpdated(function ($state, callable $set) {
                    //     if ($state) {
                    //         $data = rekonsil::where('no_transaksi', $state)->first(); 
                    //         if ($data) {
                    //             $set('nama_konsumen', $data->nama_konsumen);
                    //             $set('bank', $data->bank);
                    //             $set('max_kpr', $data->maksimal_kpr);
                    //         }
                    //     }
                    // }),

                    DatePicker::make('tanggal_mutasi')
                    ->label('Tanggal Mutasi')
                    ->required(),

                    TextInput::make('ket_dari_bank')
                    ->label('Keterangan dari Bank')
                    ->required(),

                    TextInput::make('nominal')
                    ->label('Nominal')
                    ->required(),

                    Select::make('tipe')
                    ->options([
                        'debit' => 'Debit',
                        'kredit' => 'kredit',
                    ]) ->label('Tipe')
                    ->required(),

                    TextInput::make('saldo')
                    ->label('Saldo')
                    ->required(),

                    TextInput::make('no_refrensi_bank')
                    ->label('No. Refrensi Bank')
                    ->required(),

                    TextInput::make('bank')
                    ->label('Bank')
                    ->required(),

                    TextInput::make('catatan')
                    ->label('Catatan'),

                    FileUpload::make('up_rekening_koran')
                    ->disk('public')
                    ->multiple()
                    ->required()
                    ->nullable()
                    ->label('Upload Rekening Koran')
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
            'index' => Pages\ListRekeningKorans::route('/'),
            'create' => Pages\CreateRekeningKoran::route('/create'),
            'edit' => Pages\EditRekeningKoran::route('/{record}/edit'),
        ];
    }
}
