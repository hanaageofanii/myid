<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormLegalResource\Pages;
use App\Filament\Resources\FormLegalResource\RelationManagers;
use App\Models\form_legal;
use App\Models\FormLegal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\GCVResource;
use App\Models\GCV;
use App\Models\form_kpr;
use Illuminate\Support\Facades\Storage;


class FormLegalResource extends Resource
{
    protected static ?string $model = form_legal::class;

    protected static ?string $title = "Input Sertifikat";
    protected static ?string $navigationGroup = "Legal";
    protected static ?string $pluralLabel = "Form Input Sertifikat";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Input Sertifikat';
    protected static ?string $pluralModelLabel = 'Daftar Input Sertifikat';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\Select::make('siteplan')
                        ->label('Blok')
                        ->nullable()
                        ->options(fn ($get, $set, $record) => 
                            form_kpr::where('status_akad', 'akad') 
                                ->pluck('siteplan', 'siteplan')
                                ->toArray()
                            + ($record?->siteplan ? [$record->siteplan => $record->siteplan] : [])
                        )
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $gcv = form_kpr::where('siteplan', $state)->first(); 

                            if ($gcv) {
                                $set('nama_konsumen', $gcv->nama_konsumen);
                            }
                        }),

                        Forms\Components\TextInput::make('nama_konsumen')->nullable()->label('Nama Konsumen'),

                        Forms\Components\TextInput::make('id_rumah')->nullable()->label('No. ID Rumah'),

                        Forms\Components\Select::make('status_sertifikat')
                        ->label('Status Sertifikat')
                        ->options([
                            'induk' => 'Induk',
                            'pecahan' => 'Pecahan',
                        ])->nullable(),

                        Forms\Components\TextInput::make('no_sertifikat')->nullable()->label('No. Sertifikat'),
                        Forms\Components\TextInput::make('nib')->nullable()->label('NIB'),

                        Forms\Components\TextInput::make('luas_sertifikat')->nullable()->label('Luas Sertifikat'),
                        Forms\Components\TextInput::make('imb_pbg')->nullable()->label('IMB/PBG'),

                        Forms\Components\TextInput::make('nop')->nullable()->label('NOP'),
                        Forms\Components\TextInput::make('nop1')->nullable()->label('NOP Tambahan'),

                    Forms\Components\Fieldset::make('Dokumen')
                    ->schema([
                        Forms\Components\FileUpload::make('up_sertifikat')->disk('public')->nullable()->label('Dokumen Sertifikat')->required(false),
                        Forms\Components\FileUpload::make('up_pbb')->disk('public')->nullable()->label('Dokumen PBB')->required(false),
                        Forms\Components\FileUpload::make('up_img')->disk('public')->nullable()->label('Dokumen IMG')->required(false),
                    ]),                        
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('siteplan')->sortable()->searchable()->label('Blok'),
                Tables\Columns\TextColumn::make('nama_konsumen')->sortable()->searchable()->label('Nama Konsumen'),
                Tables\Columns\TextColumn::make('id_rumah')->sortable()->searchable()->label('No. ID Rumah'),
                Tables\Columns\TextColumn::make('status_sertifikat')->sortable()->searchable()->label('Status Sertifikat'),
                Tables\Columns\TextColumn::make('no_sertifikat')->sortable()->searchable()->label('No. Sertifikat'),
                Tables\Columns\TextColumn::make('luas_sertifikat')->sortable()->searchable()->label('Luas Sertifikat'),
                Tables\Columns\TextColumn::make('nop')->sortable()->searchable()->label('NOP'),
                Tables\Columns\TextColumn::make('nop1')->sortable()->searchable()->label('NOP Tambahan'),
                Tables\Columns\TextColumn::make('up_sertifikat')
                ->label('Dokumen Sertifikat')
                ->url(fn ($record) => $record->up_sertifikat ? Storage::url($record->up_sertifikat) : '#', true)
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('up_pbb')
                ->label('Dokumen PBB')
                ->url(fn ($record) => $record->up_pbb ? Storage::url($record->up_pbb) : '#', true)
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('up_img')
                ->label('Dokumen IMG')
                ->url(fn ($record) => $record->up_img ? Storage::url($record->up_img) : '#', true)
                ->sortable()
                ->searchable(),
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
            'index' => Pages\ListFormLegals::route('/'),
            'create' => Pages\CreateFormLegal::route('/create'),
            'edit' => Pages\EditFormLegal::route('/{record}/edit'),
        ];
    }
}
