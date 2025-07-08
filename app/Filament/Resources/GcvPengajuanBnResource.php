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

class GcvPengajuanBnResource extends Resource
{
    protected static ?string $model = gcv_pengajuan_bn::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            'index' => Pages\ListGcvPengajuanBns::route('/'),
            'create' => Pages\CreateGcvPengajuanBn::route('/create'),
            'edit' => Pages\EditGcvPengajuanBn::route('/{record}/edit'),
        ];
    }
}
