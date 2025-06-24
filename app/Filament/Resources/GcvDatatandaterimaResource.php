<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvDatatandaterimaResource\Pages;
use App\Filament\Resources\GcvDatatandaterimaResource\RelationManagers;
use App\Models\gcv_datatandaterima;
use App\Models\GcvDatatandaterima;
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
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Validation\Rule;
use App\Filament\Resources\GcvDataSiteplanResource\Widgets\gcvDataSiteplanStats;

class GcvDatatandaterimaResource extends Resource
{
    protected static ?string $model = gcv_datatandaterima::class;

    protected static ?string $title = "Data Tanda Terima";
    protected static ?string $navigationGroup = "GCV";
    protected static ?string $pluralLabel = "Data Tanda Terima";
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $navigationLabel = 'Legal > Data Tanda Terima';
    protected static ?string $pluralModelLabel = 'Data Tanda Terima';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
return $form->schema([
    Wizard::make([
        Step::make('Data Umum')
            ->description('Informasi dasar kavling')
            ->schema([
                Section::make('Data Kavling')
                    ->schema([
                        TextInput::make('siteplan')
                            ->label('Site Plan')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                        TextInput::make('type')
                            ->label('Type')
                            ->required()
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                        TextInput::make('luas')
                            ->label('Luas (m²)')
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
                            ->nullable()
                            ->native(false)
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),
                    ]),
            ]),

        Step::make('Sertifikat')
            ->description('Data sertifikat tanah')
            ->schema([
                Fieldset::make('Informasi Sertifikat')
                    ->schema([
                        TextInput::make('kode1')->label('Kode 1'),
                        TextInput::make('luas1')->label('Luas 1 (m²)')->numeric(),
                        TextInput::make('kode2')->label('Kode 2'),
                        TextInput::make('luas2')->label('Luas 2 (m²)')->numeric(),
                        TextInput::make('kode3')->label('Kode 3'),
                        TextInput::make('luas3')->label('Luas 3 (m²)')->numeric(),
                        TextInput::make('kode4')->label('Kode 4'),
                        TextInput::make('luas4')->label('Luas 4 (m²)')->numeric(),
                        TextInput::make('tanda_terima_sertifikat')
                            ->label('Tanda Terima Sertifikat')
                            ->columnSpanFull(),
                    ])
                    ->columns(4)
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),
            ]),

        Step::make('Berkas Lainnya')
            ->description('Data tambahan legalitas')
            ->schema([
                Fieldset::make('Berkas Lain')
                    ->schema([
                        TextInput::make('nop_pbb_pecahan')->label('NOP / PBB Pecahan'),
                        TextInput::make('tanda_terima_nop')->label('Tanda Terima NOP'),
                        TextInput::make('imb_pbg')->label('IMB / PBG'),
                        TextInput::make('tanda_terima_imb_pbg')->label('Tanda Terima IMB/PBG'),
                        Textarea::make('tanda_terima_tambahan')
                            ->label('Tanda Terima Tambahan')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),
            ]),

        Step::make('Upload Berkas')
            ->description('Unggah dokumen pendukung')
            ->schema([
                Fieldset::make('Upload Dokumen')
                    ->schema([
                        FileUpload::make('up_sertifikat')
                            ->label('Upload Sertifikat')
                            ->disk('public')->multiple()->nullable()
                            ->downloadable()->previewable(false),

                        FileUpload::make('up_nop')
                            ->label('Upload NOP')
                            ->disk('public')->multiple()->nullable()
                            ->downloadable()->previewable(false),

                        FileUpload::make('up_imb_pbg')
                            ->label('Upload IMB/PBG')
                            ->disk('public')->multiple()->nullable()
                            ->downloadable()->previewable(false),

                        FileUpload::make('up_tambahan_lainnya')
                            ->label('Upload Tambahan Lainnya')
                            ->disk('public')->multiple()->nullable()
                            ->downloadable()->previewable(false),
                    ])
                    ->columns(2)
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
                    })()),
            ]),
    ])
    ->submitAction(
        Action::make('submit')
            ->label('Simpan')
    )
    ->columnSpanFull(),
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
            'index' => Pages\ListGcvDatatandaterimas::route('/'),
            'create' => Pages\CreateGcvDatatandaterima::route('/create'),
            'edit' => Pages\EditGcvDatatandaterima::route('/{record}/edit'),
        ];
    }
}
