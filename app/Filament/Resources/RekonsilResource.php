<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekonsilResource\Pages;
use App\Filament\Resources\RekonsilResource\RelationManagers;
use App\Models\Rekonsil;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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


class RekonsilResource extends Resource
{
    protected static ?string $model = Rekonsil::class;

    protected static ?string $title = "Input Transaksi Internal";
    protected static ?string $navigationGroup = "Kasir";
    protected static ?string $pluralLabel = "Transaksi Internal";
    protected static ?string $navigationLabel = "Transaksi Internal";
    protected static ?string $pluralModelLabel = 'Daftar Transaksi Internal';
    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make()
                ->schema([
                    TextInput::make('no_transaksi')
                    ->label('No. Transaksi')
                    ->required(),

                    DatePicker::make('tanggal_transaksi')
                    ->label('Tanggal Transaksi')
                    ->required(),

                    TextInput::make('nama_yang_mencairkan')
                    ->label(' Nama yang Mencairkan')
                    ->required(),

                    DatePicker::make('tanggal_diterima')
                    ->label('Tanggal di Terima')
                    ->required(),

                    TextInput::make('nama_penerima')
                    ->label('Nama Penerima')
                    ->required(),

                    TextInput::make('bank')
                    ->label('Bank')
                    ->required(),

                    TextArea::make('deskripsi')
                    ->label('Deskripsi Keperluan')
                    ->required(),

                    TextInput::make('jumlah_uang')
                    ->label('Jumlah Uanh')
                    ->required(),

                    Select::make('tipe')
                    ->options([
                        'debit' => 'Debit',
                        'kredit' => 'kredit',
                    ]) ->label('Tipe')
                    ->required(),

                    Select::make('status_rekonsil')
                    ->options([
                        'belum' => 'Belum',
                        'sudah' => 'Sudah'
                    ]) ->label('Status Rekonsil')
                    ->required(),

                    TextArea::make('catatan')
                    ->label('Catatan')
                    ->required(),
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
            'index' => Pages\ListRekonsils::route('/'),
            'create' => Pages\CreateRekonsil::route('/create'),
            'edit' => Pages\EditRekonsil::route('/{record}/edit'),
        ];
    }
}
