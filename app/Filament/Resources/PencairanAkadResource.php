<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PencairanAkadResource\Pages;
use App\Filament\Resources\PencairanAkadResource\RelationManagers;
use App\Models\pencairan_akad;
use App\Models\PencairanAkad;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\form_dp;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
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
use App\Models\form_kpr;
use App\Models\FormKpr;
use App\Models\Audit;
use App\Filament\Resources\GCVResource;
use App\Models\GCV;
use App\Filament\Resources\KPRStats;

class PencairanAkadResource extends Resource
{
    protected static ?string $model = pencairan_akad::class;

    protected static ?string $title = "Form Input Data Pencairan Akad";
    protected static ?string $navigationGroup = "Legal";
    protected static ?string $pluralLabel = "Data Pencairan Akad";
    protected static ?string $navigationLabel = "Pencairan Akad";
    protected static ?string $pluralModelLabel = 'Daftar Pencairan';
    protected static ?string $navigationIcon = 'heroicon-o-folder-arrow-down';
    public static function form(Form $form): Form
    {
        return $form->schema([
            Fieldset::make('Data Konsumen')
            ->schema([
                Select::make('siteplan')
                    ->label('Site Plan')
                    ->options(fn () => form_kpr::pluck('siteplan', 'siteplan')) 
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $data = form_kpr::where('siteplan', $state)->first(); 
                            if ($data) {
                                $set('nama_konsumen', $data->nama_konsumen);
                                $set('bank', $data->bank);
                                $set('max_kpr', $data->maksimal_kpr);
                            }
                        }
                    }),
        
                Select::make('bank')
                    ->options([
                        'btn_cikarang' => 'BTN Cikarang',
                        'btn_bekasi' => 'BTN Bekasi',
                        'btn_karawang' => 'BTN Karawang',
                        'bjb_syariah' => 'BJB Syariah',
                        'bjb_jababeka' => 'BJB Jababeka',
                        'btn_syariah' => 'BTN Syariah',
                        'brii_bekasi' => 'BRI Bekasi',
                    ])
                    ->required()
                    ->label('Bank'),
                
                    TextInput::make('nama_konsumen')
                    ->label('Nama Konsumen')
                    ->dehydrated(),
                
                TextInput::make('max_kpr')
                    ->label('Maksimal KPR')
                    ->prefix('Rp')
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set, $get) => 
                    $set('dana_jaminan', max(0, (int) $state - (int) $get('nilai_pencairan'))))
                    ->dehydrated(),
            ]),  
            
            Fieldset::make('Pembayaran')
            ->schema([
                DatePicker::make('tanggal_pencairan')
                ->required()
                ->label('Tanggal Pencarian Akad'),

            TextInput::make('nilai_pencairan')
                ->label('Nilai Pencairan')
                ->prefix('Rp')
                ->dehydrated()
                ->afterStateUpdated(fn ($state, callable $set, $get) => 
        $set('dana_jaminan', max(0, (int) $get('max_kpr') - (int) $state)))
                ->reactive(),
            
                TextInput::make('dana_jaminan')
                ->label('Dana Jaminan')
                ->prefix('Rp')
                ->reactive() 
                ->dehydrated(),
            ]),



            Fieldset::make('Dokumen')
                ->schema([
                    FileUpload::make('up_rekening_koran')->disk('public')->nullable()->label('Rekening Koran')
                        ->downloadable()->previewable(false),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siteplan')->searchable()->label('Blok'),
                TextColumn::make('bank')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                        'btn_cikarang' => 'BTN Cikarang',
                        'btn_bekasi' => 'BTN Bekasi',
                        'btn_karawang' => 'BTN Karawang',
                        'bjb_syariah' => 'BJB Syariah',
                        'bjb_jababeka' => 'BJB Jababeka',
                        'btn_syariah' => 'BTN Syariah',
                        'brii_bekasi' => 'BRI Bekasi',
                default => ucfirst($state), 
            })
            ->sortable()
            ->searchable()
            ->label('Bank'),
            TextColumn::make('nama_konsumen')->searchable()->label('Nama Konsumen'),
            TextColumn::make('max_kpr')
            ->searchable()
            ->label('Max KPR')
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
            TextColumn::make('tanggal_pencairan')
            ->searchable()
            ->label('Tanggal Pencairan')
            ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
            TextColumn::make('nilai_pencairan')
            ->searchable()
            ->label('Nilai Pencairan')
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
            TextColumn::make('dana_jaminan')
            ->searchable()
            ->label('Nilai Pencairan')            
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),


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
            'index' => Pages\ListPencairanAkads::route('/'),
            'create' => Pages\CreatePencairanAkad::route('/create'),
            'edit' => Pages\EditPencairanAkad::route('/{record}/edit'),
        ];
    }
}
