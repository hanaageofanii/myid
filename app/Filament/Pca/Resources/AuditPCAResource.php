<?php

namespace App\Filament\Pca\Resources;

use App\Filament\Pca\Resources\AuditPCAResource\Pages;
use App\Filament\Pca\Resources\AuditPCAResource\RelationManagers;
use App\Models\AuditPCA;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Audit;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\AuditResource\Widgets\AuditStats;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AuditPCAResource extends Resource
{
    protected static ?string $model = AuditPCA::class;

    protected static ?string $title = "Audit PCA";
    protected static ?string $navigationGroup = "Legal";
    protected static ?string $pluralLabel = "Audit PCA";
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Audit PCA';
    protected static ?string $pluralModelLabel = 'Daftar Audit PCA';
    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('siteplan')
            ->required()
            ->label('Site Plan')
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Legal officer']);
            })())
            ->unique(ignoreRecord: true),

            
            TextInput::make('type')
                ->label('Type')
                ->required()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal officer']);
                })()),

            Toggle::make('terbangun')
                ->label('Terbangun')
                
                ->default(false)
                ->onColor('success')
                ->offColor('danger')
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal officer']);
                })()),

            Select::make('status')
                ->label('Status')
                
                ->options([
                    'akad' => 'Akad',
                ])
                ->nullable()->native(false)
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal officer']);
                })()),

            Fieldset::make('Sertifikat')
                ->schema([
                    TextInput::make('kode1')
                    ->label('Kode 1')
                    
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('luas1')
                    ->label('Luas 1 (m²)')
                    
                    ->numeric()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('kode2')
                    ->label('Kode 2')
                    
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('luas2')
                    ->label('Luas 2 (m²)')
                    ->numeric()
                    
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),
                    
                    TextInput::make('kode3')
                    ->label('Kode 3')
                    
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('luas3')
                    ->label('Luas 3 (m²)')
                    ->numeric()
                    
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('kode4')
                    ->label('Kode 4')
                    
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('luas4')
                    ->label('Luas 4 (m²)')
                    ->numeric()
                    
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('tanda_terima_sertifikat')
                    ->label('Tanda Terima Sertifikat')
                    ->columnSpanFull()
                    
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),
                ])
                ->columns(4),

            Fieldset::make('Berkas Lainnya')
                ->schema([
                    TextInput::make('nop_pbb_pecahan')
                    ->label('NOP / PBB Pecahan')
                    
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('tanda_terima_nop')
                    ->label('Tanda Terima NOP')
                    
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('imb_pbg')
                    ->label('IMB / PBG')
                    
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('tanda_terima_imb_pbg')
                    ->label('Tanda Terima IMB/PBG')
                    
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    Textarea::make('tanda_terima_tambahan')
                    ->label('Tanda Terima Tambahan')
                    
                    ->rows(3)->columnSpanFull()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),
                ])
                ->columns(2),
            
                Fieldset::make('Upload Berkas')
                ->schema([
                    FileUpload::make('up_sertifikat')
                        ->disk('public')
                        ->multiple()
                        ->nullable()
                        
                        ->label('Upload Sertifikat')
                        ->downloadable()
                        ->previewable(false)
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })()),
                    
                    FileUpload::make('up_nop')
                        ->disk('public')
                        ->nullable()
                        ->multiple()
                        
                        ->label('Upload NOP')
                        ->downloadable()
                        ->previewable(false)
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })()),

                    FileUpload::make('up_imb_pbg')
                        ->disk('public')
                        ->nullable()
                        ->multiple()
                        
                        ->label('Upload IMB/PBG')
                        ->downloadable()
                        ->previewable(false)
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })()),
                    
                    FileUpload::make('up_tambahan_lainnya')
                        ->disk('public')
                        ->nullable()
                        ->multiple()
                        
                        ->label('Upload Tambahan Lainnya')
                        ->downloadable()
                        ->previewable(false)
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })()),
                    
                ])
                ->columns(2),
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
            'index' => Pages\ListAuditPCAS::route('/'),
            'create' => Pages\CreateAuditPCA::route('/create'),
            'edit' => Pages\EditAuditPCA::route('/{record}/edit'),
        ];
    }
}
