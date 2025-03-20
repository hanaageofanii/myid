<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AjbResource\Pages;
use App\Filament\Resources\AjbResource\RelationManagers;
use App\Models\Ajb;
use App\Models\form_kpr;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\PencairanAkad;
use App\Models\form_dp;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
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
use App\Models\FormKpr;
use App\Models\Audit;
use App\Filament\Resources\FormLegalResource;
use App\Models\GCV;
use App\Filament\Resources\KPRStats;
use App\Models\form_legal;
use App\Models\form_pajak;



class AjbResource extends Resource
{
    protected static ?string $model = Ajb::class;

    protected static ?string $title = "AJB";
    protected static ?string $navigationGroup = "Legal";
    protected static ?string $pluralLabel = "AJB";
    protected static ?string $navigationIcon = 'heroicon-o-document';
    protected static ?string $navigationLabel = 'AJB';
    protected static ?string $pluralModelLabel = 'Daftar AJB';
    public static function form(Form $form): Form
    {
        return $form->schema([
            Fieldset::make('Data Konsumen')
            ->schema([
                Select::make('siteplan')
                ->label('Blok')
                ->options(fn () => form_kpr::pluck('siteplan', 'siteplan')) 
                ->searchable()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $kprData = form_kpr::where('siteplan', $state)->first();
                    if ($kprData) {
                        $set('kavling', $kprData->jenis_unit);
                        $set('nama_konsumen', $kprData->nama_konsumen);
                        $set('nik', $kprData->nik);
                        $set('npwp', $kprData->npwp);
                        $set('alamat', $kprData->alamat);
                        $set('harga', $kprData->harga);
                        $set('pembayaran', $kprData->pembayaran);
                }
            

                        $legalData = form_legal::where('siteplan', $state)->first();
                        if ($legalData) {
                            $set('nop', $legalData->nop);
                        }

                        // $pajakData = form_pajak::where('siteplan', $state)->first();
                        // if ($pajakData) {
                        //     $set('nop', $pajakData->nop);
                        // }
                    }),
                    TextInput::make('nop')
                    ->nullable  ()
                    ->label('NOP')
                    ->reactive(),

                    TextInput::make('nama_konsumen')
                    ->nullable()
                    ->label('Nama Konsumen')
                    ->reactive(),

                    TextInput::make('nik')
                    ->nullable()
                    ->label('NIK')
                    ->reactive(),

                    TextInput::make('npwp')
                    ->nullable()
                    ->label('NPWP')
                    ->reactive(),

                    TextArea::make('alamat')
                    ->nullable()
                    ->label('Alamat')
                    ->reactive(),
                ]),

                Fieldset::make('Data AJB')
                ->schema([
                    TextInput::make('no_suket_validasi')
                    ->nullable()
                    ->label('No. Suket Validasi'),

                    TextInput::make('no_sspd_bptb')
                    ->nullable()
                    ->label('No. SSPD BPHTB'),

                    DatePicker::make('tanggal_sspd_bphtb')
                    ->nullable()
                    ->label('Tanggal SSPD BPHTB'),

                    TextInput::make(   'no_validasi_sspd_bphtb')
                    ->nullable()
                    ->label('No. Validasi SSPD BPHTB'),

                    TextInput::make('notaris')
                    ->nullable()
                    ->label('Notaris'),

                    TextInput::make('no_ajb')
                    ->nullable()
                    ->label('No. AJB'),

                    DatePicker::make('tanggal_ajb')
                    ->nullable()
                    ->label('Tanggal AJB'),

                    TextInput::make('no_bast')
                    ->nullable()
                    ->label('No. Bast'),

                    DatePicker::make(  'tanggal_bast')
                    ->nullable()
                    ->label('Tanggal Bast'),
                ]),
                Fieldset::make('Dokumen')
                ->schema([
                    FileUpload::make('up_validasi_bphtb')
                    ->disk('public')
                    ->nullable()
                    ->label('Validasi BPHTB')
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
            'index' => Pages\ListAjbs::route('/'),
            'create' => Pages\CreateAjb::route('/create'),
            'edit' => Pages\EditAjb::route('/{record}/edit'),
        ];
    }
}
