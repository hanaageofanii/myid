<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Filament\Resources\PermissionResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Permission;
use Filament\Forms\Components\Card;
use App\Models\Team;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $title = "Permission";
    // protected static ?string $tenantOwnershipRelationshipName = null;

    protected static ?string $navigationGroup = "Settings";
    protected static ?string $pluralLabel = "Permission";
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Permission';
    protected static ?string $pluralModelLabel = 'Permission';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make ('name')
                ->minLength(2)
                ->maxLength(255)
                ->label('Nama')
                ->required()

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               TextColumn::make('id')->sortable()->searchable()->label('Id'),
               TextColumn::make('name')->label('Nama')->searchable(),
               TextColumn::make('created_at')
                    ->dateTime('d-M-Y')->sortable()
                    ->searchable()
                    ->label('Created at'),
                    // ,


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
