<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KartuKontrolGCVResource\Pages;
use App\Models\Kartu_kontrolGCV;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class KartuKontrolGCVResource extends Resource
{
    protected static ?string $model = Kartu_kontrolGCV::class;

    protected static ?string $title = "Form Kartu Kontrol";
    protected static ?string $navigationGroup = "GCV";
    protected static ?string $pluralLabel = "Kartu Kontrol";
    protected static ?string $navigationLabel = "Keuangan > Kartu Kontrol";
    protected static ?string $pluralModelLabel = 'Kartu Kontrol';
    protected static ?string $navigationIcon = 'heroicon-o-cursor-arrow-ripple';
    protected static ?int $navigationSort = 18;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->extraAttributes(['class' => 'centered-container'])
            ->schema([
                Wizard::make()
                    ->steps([
                        Wizard\Step::make('Informasi')
                            ->description('Data Proyek')
                            ->schema([
                                Section::make('Informasi')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('nama_proyek')
                                            ->label('Nama Proyek')
                                            ->required()
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                    $user = Auth::user();
                                                        return $user && $user->hasRole(['admin','Legal officer']);
                                                })()),
                                        TextInput::make('lokasi_proyek')
                                            ->label('Lokasi Proyek')
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin','Legal officer']);
                                            })()),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tambahkan kolom sesuai kebutuhan
            ])
            ->filters([
                // Tambahkan filter jika perlu
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
            // Tambahkan RelationManagers jika ada
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKartuKontrolGCVS::route('/'),
            'create' => Pages\CreateKartuKontrolGCV::route('/create'),
            'edit' => Pages\EditKartuKontrolGCV::route('/{record}/edit'),
        ];
    }
}
