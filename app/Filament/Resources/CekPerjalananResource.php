<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CekPerjalananResource\Pages;
use App\Filament\Resources\CekPerjalananResource\RelationManagers;
use App\Models\cek_perjalanan;
use App\Models\CekPerjalanan;
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
use App\Models\rekening_koran;


class CekPerjalananResource extends Resource
{
    protected static ?string $model = cek_perjalanan::class;

    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = "Kasir";
    protected static ?string $pluralLabel = "Cek Rekening & Transkasi Internal";
    protected static ?string $navigationLabel = "Cek Rekening & Transkasi Internal";
    protected static ?string $pluralModelLabel = 'Daftar Cek Rekening & Transkasi Internal';
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make()
                ->schema([
                    Select::make('no_referensi_bank')
                    ->label('No. Referensi Bank')
                    ->options(fn () => rekening_koran::pluck('no_referensi_bank', 'no_referensi_bank'))
                    ->searchable()
                    ->reactive(),                
                    
                    Select::make('no_transaksi')
                    ->label('No. Transaksi')
                    ->options(fn () => rekonsil::pluck('no_transaksi', 'no_transaksi'))
                    ->searchable()
                    ->reactive(),

                    TextInput::make('nama_pencair')
                    ->required()
                    ->label('Nama Pencair'),

                    DatePicker::make('tanggal_dicairkan')
                    ->required()
                    ->label('Tanggal di Cairkan'),

                    TextInput::make('nama_penerima')
                    ->required()
                    ->label('Nama Penerima'),

                    DatePicker::make('tanggal_diterima')
                    ->required()
                    ->label('Tanggal di Terima'),

                    TextArea::make('tujuan_dana')
                    ->required()
                    ->label('Tujuan Dana'),

                    Select::make('status_disalurkan')
                    ->options([
                        'sudah' => 'Sudah',
                        'belum' => 'Belum',
                    ]) ->label('Status di Salurkan')
                    ->required(),

                    FileUpload::make('bukti_pendukung')
                    ->disk('public')
                    ->multiple()
                    ->required()
                    ->nullable()
                    ->label('Bukti Pendukung')
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
            'index' => Pages\ListCekPerjalanans::route('/'),
            'create' => Pages\CreateCekPerjalanan::route('/create'),
            'edit' => Pages\EditCekPerjalanan::route('/{record}/edit'),
        ];
    }
}
