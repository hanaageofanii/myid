<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PencairanAkadResource\Pages;
use App\Filament\Resources\PencairanAkadResource\RelationManagers;
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
use Illuminate\Support\Facades\Auth;

class PencairanAkadResource extends Resource
{
    protected static ?string $model = PencairanAkad::class;

    protected static ?string $title = "Form Input Data Pencairan Akad";
    protected static ?string $navigationGroup = "Keuangan";
    protected static ?string $pluralLabel = "Data Pencairan Akad";
    protected static ?string $navigationLabel = "Pencairan Akad";
    protected static ?string $pluralModelLabel = 'Daftar Pencairan';
    protected static ?string $navigationIcon = 'heroicon-o-cloud-arrow-up';
    public static function form(Form $form): Form
    {
        return $form->schema([
            Fieldset::make('Data Konsumen')
            ->schema([
                Select::make('siteplan')
                    ->label('Blok')
                    ->options(fn () => form_kpr::pluck('siteplan', 'siteplan')) 
                    ->searchable()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 1']);
                    })())
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
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 1']);
                    })())
                    ->label('Bank'),
                
                    TextInput::make('nama_konsumen')
                    ->label('Nama Konsumen')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 1']);
                    })())
                    ->dehydrated(),
                
                TextInput::make('max_kpr')
                    ->label('Maksimal KPR')
                    ->prefix('Rp')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 1']);
                    })())
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set, $get) => 
                    $set('dana_jaminan', max(0, (int) $state - (int) $get('nilai_pencairan'))))
                    ->dehydrated(),
            ]),  
            
            Fieldset::make('Pembayaran')
            ->schema([
                DatePicker::make('tanggal_pencairan')
                ->required()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Kasir 1']);
                })())
                ->label('Tanggal Pencarian Akad'),

            TextInput::make('nilai_pencairan')
                ->label('Nilai Pencairan')
                ->prefix('Rp')
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Kasir 1']);
                })())
                ->dehydrated()
                ->afterStateUpdated(fn ($state, callable $set, $get) => 
                $set('dana_jaminan', max(0, (int) $get('max_kpr') - (int) $state)))
                ->reactive(),
            
                TextInput::make('dana_jaminan')
                ->label('Dana Jaminan')
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Kasir 1']);
                })())
                ->prefix('Rp')
                ->reactive() 
                ->dehydrated(),
            ]),



            Fieldset::make('Dokumen')
                ->schema([
                    FileUpload::make('up_rekening_koran')->disk('public')->nullable()->label('Rekening Koran')
                        ->downloadable()->multiple()->previewable(false)
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })()),
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
            ->label('Dana Jaminan')            
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),

            TextColumn::make('up_rekening_koran')
            ->label('Rekening Koran')
            ->formatStateUsing(function ($record) {
                if (!$record->up_rekening_koran) {
                    return 'Tidak Ada Dokumen';
                }

                $files = is_array($record->up_rekening_koran) ? $record->up_rekening_koran : json_decode($record->up_rekening_koran, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    $files = [];
                }

                $output = '';
                foreach ($files as $file) {
                    $url = Storage::url($file);
                    $output .= '<a href="' . $url . '" target="_blank">Lihat</a> | <a href="' . $url . '" download>Download</a><br>';
                }

                return $output ?: 'Tidak Ada Dokumen';
            })
            ->html()
            ->sortable(),

            ])

            ->defaultSort('siteplan', 'asc')
            ->headerActions([
                Action::make('count')
                    ->label(fn ($livewire): string => 'Total: ' . $livewire->getFilteredTableQuery()->count())
                    ->disabled(),
            ])
            ->filters([
                TrashedFilter::make()
                ->label('Data yang dihapus') 
                ->native(false),

                Filter::make('bank')
                    ->label('Bank')
                    ->form([
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
                            ->nullable()
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['bank']), fn ($q) =>
                            $q->where('bank', $data['bank'])
                        )
                    ),
                    Filter::make('created_from')
                    ->label('Dari Tanggal')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Dari')
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when($data['created_from'] ?? null, fn ($q) =>
                            $q->whereDate('created_at', '>=', $data['created_from'])
                        )
                    ),
                
                Filter::make('created_until')
                    ->label('Sampai Tanggal')
                    ->form([
                        DatePicker::make('created_until')
                            ->label('Sampai')
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when($data['created_until'] ?? null, fn ($q) =>
                            $q->whereDate('created_at', '<=', $data['created_until'])
                        )
                    ),                
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormMaxHeight('400px')
            ->filtersFormColumns(4)
            ->filtersFormWidth(MaxWidth::FourExtraLarge)
                                    
            ->actions([
                ActionGroup::make([
                    ViewAction::make()
                        ->color('success')
                        ->label('Lihat'),
                    EditAction::make()
                        ->color('info')
                        ->label('Ubah')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Pencairan Akad Diubah')
                                ->body('Data Pencarian Akad telah berhasil disimpan.')),                    
                        DeleteAction::make()
                        ->color('danger')
                        ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                        ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok{$record->siteplan}")
                        ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Pencarian Akad Dihapus')
                                ->body('Data Pencarian Akad telah berhasil dihapus.')),                            
                    // RestoreAction::make()
                    //     ->label('Pulihkan')
                    //     ->successNotificationTitle('Data berhasil dipulihkan')
                    //     ->successRedirectUrl(route('filament.admin.resources.audits.index')),
                    RestoreAction::make()
                    ->color('info')
                    ->label(fn ($record) => "Kembalikan {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Kembalikan Blok{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengembalikan blok {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Pencarian Akad')
                            ->body('Data Pencarian Akad berhasil dikembalikan.')
                    ),
                    ForceDeleteAction::make()
                    ->color('primary')
                    ->label(fn ($record) => "Hapus Permanent {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok Permanent{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus blok secara permanent {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Pencarian Akad')
                            ->body('Data Pencarian Akad berhasil dihapus secara permanen.')
                    ),
                    ])->button()->label('Action'),
                ], position: ActionsPosition::BeforeCells)
            
                ->groupedBulkActions([
                    BulkAction::make('delete')
                        ->label('Hapus')
                        ->icon('heroicon-o-trash') 
                        ->color('danger')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Pencarian Akadg')
                                ->body('Data Pencarian Akad berhasil dihapus.'))                        
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),
                
                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle') 
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Pencarian Akad')
                                ->body('Data Pencarian Akad berhasil dihapus secara permanen.'))                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->forceDelete()),
                
                    BulkAction::make('export')
                        ->label('Download Data')
                        ->icon('heroicon-o-arrow-down-tray') 
                        ->color('info')
                        ->action(fn (Collection $records) => static::exportData($records)),
                
                    RestoreBulkAction::make()
                        ->label('Kembalikan Data')
                        ->icon('heroicon-o-arrow-path') 
                        ->color('success')
                        ->button()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Pencarian Akad')
                                ->body('Data Pencarian Akad berhasil dikembalikan.')),
                ]);
    }

    public static function exportData(Collection $records)
    {
        $csvData = "ID, Blok, Bank, Nama Konsumen, Maksimal KPR, Tanggal Pencairan, Nilai Pencairan, Dana Jaminan\n";
    
        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->siteplan}, {$record->bank}, {$record->nama_konsumen}, {$record->max_kpr}, {$record->tanggal_pencairan}, {$record->nilai_pencairan}, {$record->dana_jaminan}\n";
        }
    
        return response()->streamDownload(fn () => print($csvData), 'PencairanAkad.csv');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
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
