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
                //
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
