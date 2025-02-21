<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditResource\Pages;
use App\Filament\Resources\AuditResource\RelationManagers;
use App\Models\Audit;
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
class AuditResource extends Resource
{
    protected static ?string $model = Audit::class;

    protected static ?string $title = "Audit";

    protected static ?string $navigationGroup = "Legal";

    protected static ?string $pluralLabel = "Audit";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('siteplan')
            ->required()
            ->label('Site Plan'),  
            
            TextInput::make('type')
                ->label('Type')
                ->required(),

            Toggle::make('terbangun')
                ->label('Terbangun')
                ->default(false),

            Select::make('status')
                ->label('Status')
                ->options([
                    'terjual' => 'Terjual',
                    'belum' => 'Belum',
                ])
                ->required(),

            // Grup SERTIPIKAT
            Fieldset::make('Sertifikat')
                ->schema([
                    TextInput::make('kode1')->label('Kode 1'),
                    TextInput::make('luas1')->label('Luas 1 (m²)')->numeric(),
                    TextInput::make('kode2')->label('Kode 2'),
                    TextInput::make('luas2')->label('Luas 2 (m²)')->numeric(),
                    TextInput::make('kode3')->label('Kode 3'),
                    TextInput::make('luas3')->label('Luas 3 (m²)')->numeric(),
                    TextInput::make('kode4')->label('Kode 4'),
                    TextInput::make('luas4')->label('Luas 4 (m²)')->numeric(),
                    TextInput::make('tanda_terima_sertifikat')->label('Tanda Terima Sertifikat')->columnSpanFull(),
                ])
                ->columns(2),

            // Grup TANDA TERIMA
            Fieldset::make('Tanda Terima')
                ->schema([
                    TextInput::make('nop_pbb_pecahan')->label('NOP / PBB Pecahan'),
                    TextInput::make('tanda_terima_nop')->label('Tanda Terima NOP'),
                    TextInput::make('imb_pbg')->label('IMB / PBG'),
                    TextInput::make('tanda_terima_imb_pbg')->label('Tanda Terima IMB/PBG'),
                    Textarea::make('tanda_terima_tambahan')->label('Tanda Terima Tambahan')->rows(3)->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('siteplan')->label('Site Plan'),
            TextColumn::make('type')->label('Type')->searchable(),
            BooleanColumn::make('terbangun')->label('Terbangun'),
            TextColumn::make('status')->label('Status')->badge(),
            TextColumn::make('kode1')->label('Kode 1'),
            TextColumn::make('luas1')->label('Luas 1 (m²)'),
            TextColumn::make('kode2')->label('Kode 2'),
            TextColumn::make('luas2')->label('Luas 2 (m²)'),
            TextColumn::make('kode3')->label('Kode 3'),
            TextColumn::make('luas3')->label('Luas 3 (m²)'),
            TextColumn::make('kode4')->label('Kode 4'),
            TextColumn::make('luas4')->label('Luas 4 (m²)'),

            TextColumn::make('sertifikat')->label('Tanda Terima Sertifikat'),

            // Tampilkan Tanda Terima
            TextColumn::make('nop_pbb_pecahan')->label('NOP / PBB Pecahan')->limit(20),
            TextColumn::make('tanda_terima_nop')->label('Tanda Terima NOP')->limit(20),
            TextColumn::make('imb_pbg')->label('IMB / PBG')->limit(20),
            TextColumn::make('tanda_terima_imb_pbg')->label('Tanda Terima IMB/PBG')->limit(20),
            TextColumn::make('tanda_terima_tambahan')->label('Tanda Terima Tambahan')->limit(50),
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
            'index' => Pages\ListAudits::route('/'),
            'create' => Pages\CreateAudit::route('/create'),
            'edit' => Pages\EditAudit::route('/{record}/edit'),
        ];
    }
}
