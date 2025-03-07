<?php  

namespace App\Filament\Resources;

use App\Filament\Resources\GCVResource\Pages;
use App\Models\GCV;
use App\Models\Audit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\AuditResource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\GCVResource\Widgets\GCVStats;






class GCVResource extends Resource
{
    protected static ?string $model = GCV::class;
    protected static ?string $title = "Grand Cikarang Village";
    protected static ?string $navigationGroup = "Legal";
    protected static ?string $pluralLabel = "GCV";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'GCV';
    protected static ?string $pluralModelLabel = 'Data GCV';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('proyek')
                    ->options([
                        'gcv_cira' => 'GCV Cira',
                        'gcv' => 'GCV',
                        'tkr' => 'TKR',
                        'pca1' => 'PCA1',
                    ])
                    ->label('Proyek')
                    ->required(),

                Forms\Components\Select::make('nama_perusahaan')
                    ->label('Nama Perumahan')
                    ->options([
                        'grand_cikarang_village' => 'Grand Cikarang Village',
                        'taman_kertamukti_residence' => 'Taman Kertamukti Residence',
                        'pesona_cengkong_asri_1' => 'Pesona Cengkong Asri 1',
                    ])
                    ->label('Nama Perusahaan')
                    ->required(),

                Forms\Components\Select::make('kavling')
                    ->options([
                        'standar' => 'Standar',
                        'khusus' => 'Khusus',
                        'hook' => 'Hook',
                        'komersil' => 'Komersil',
                        'tanah_lebih' => 'Tanah Lebih',
                        'kios' => 'Kios'
                    ])
                    ->label('Kavling')
                    ->required(),

                    Forms\Components\Select::make('siteplan')
                        ->label('Blok')
                        ->options(Audit::pluck('siteplan', 'siteplan')->toArray()) 
                        ->searchable()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $audit = Audit::where('siteplan', $state)->first(); 

                            if ($audit) {
                                $set('kpr_status', $audit->status === 'akad' ? 'akad' : null);
                                $set('type', $audit->type);
                            }
                        }),

                
                Forms\Components\TextInput::make('type')
                    ->label('Type')
                    ->required(),
                

                Forms\Components\TextInput::make('luas_tanah')
                    ->numeric()
                    ->label('Luas Tanah')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'booking' => 'Booking',
                    ])
                    ->label('Status'),
                    // ->required(),

                Forms\Components\DatePicker::make('tanggal_booking')
                    ->label('Tanggal Booking')
                    ,

                Forms\Components\TextInput::make('nama_konsumen')
                    ->label('Nama Konsumen'),

                Forms\Components\TextInput::make('agent')
                    ->label('Agent'),

                Forms\Components\Select::make('kpr_status')
                    ->options([
                        'sp3k' => 'SP3K',
                        'akad' => 'Akad',
                        'batal' => 'Batal',
                    ])
                    ->label('KPR Status'),

                Forms\Components\Textarea::make('ket')
                    ->label('Keterangan')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('proyek')->label('Proyek')
                ->formatStateUsing(fn (string $state): string => match ($state)
                {
                        'gcv_cira' => 'GCV Cira',
                        'gcv' => 'GCV',
                        'tkr' => 'TKR',
                        'pca1' => 'PCA1',
                        default => $state, 
                }),
                Tables\Columns\TextColumn::make('nama_perusahaan')->label('Nama Perumahan')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'grand_cikarang_village' => 'Grand Cikarang Village',
                    'taman_kertamukti_residence' => 'Taman Kertamukti Residence',
                    'pesona_cengkong_asri_1' => 'Pesona Cengkong Asri 1',
                    default => $state, 
                }),

                Tables\Columns\TextColumn::make('kavling')->label('Kavling')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'standar' => 'Standar',
                    'khusus' => 'Khusus',
                    'hook' => 'Hook',
                    'komersil' => 'Komersil',
                    'tanah_lebih' => 'Tanah Lebih',
                    'kios' => 'Kios',
                    default => $state, 
                }),

                Tables\Columns\TextColumn::make('siteplan')->label('Blok'),
                Tables\Columns\TextColumn::make('type')->label('Type'),
                Tables\Columns\TextColumn::make('luas_tanah')->label('Luas Tanah'),
                Tables\Columns\TextColumn::make('status')->label('Status')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'booking' => 'Booking',
                    default => $state, 
                }),

                Tables\Columns\TextColumn::make('tanggal_booking')->date()->label('Tanggal Booking'),
                Tables\Columns\TextColumn::make('nama_konsumen')->label('Nama Konsumen'),
                Tables\Columns\TextColumn::make('agent')->label('Agent'),
                Tables\Columns\TextColumn::make('kpr_status')
                    ->label('KPR Status')
                    ->default(fn ($record) => $record->audits?->status === 'akad' ? 'Akad' : $record->kpr_status)
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'sp3k' => 'SP3K',
                        'akad' => 'Akad',
                        'batal' => 'Batal',
                        default => $state, 
                    }),
            ])
            ->defaultSort('siteplan', 'asc')
            ->headerActions([
                Action::make('count')
                ->label(fn ($livewire): string => 'Total: ' . $livewire->getFilteredTableQuery()->count())
                ->disabled(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'booking' => 'Booking',
                    ]) ->native(false),
            
                Tables\Filters\TrashedFilter::make()
                    ->label('Data yang dihapus')
                    ->native(false),

                    
            
                Tables\Filters\SelectFilter::make('kpr_status') 
                    ->label('Status KPR')
                    ->options([
                        'sp3k' => 'SP3K',
                        'akad' => 'Akad',
                        'batal' => 'Batal',
                    ])
                    ->native(false),

                Tables\Filters\SelectFilter::make('proyek') 
                    ->label('Proyek')
                    ->options([
                        'gcv_cira' => 'GCV Cira',
                        'gcv' => 'GCV',
                    ])
                    ->native(false),
            
                Filter::make('created_from')
                    ->label('Dari Tanggal')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Dari'),
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
                            ->label('Sampai'),
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
                                ->title('Data GCV Diperbarui')
                                ->body('Data GCV telah berhasil disimpan.')),                    
                        DeleteAction::make()
                        ->color('danger')
                        ->label('Hapus')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data GCV Dihapus')
                                ->body('Data GCV telah berhasil dihapus.')),
                    // RestoreAction::make()
                    //     ->label('Pulihkan')
                    //     ->successNotificationTitle('Data berhasil dipulihkan')
                    //     ->successRedirectUrl(route('filament.admin.resources.audits.index')),
                    Tables\Actions\RestoreAction::make()
                    ->color('info')
                    ->label('Kembalikan Data')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data GCV')
                            ->body('Data GCV berhasil dikembalikan.')
                    ),
                    Tables\Actions\ForceDeleteAction::make()
                    ->color('primary')
                    ->label('Hapus Permanen')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data GCV')
                            ->body('Data GCV berhasil dihapus secara permanen.')
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
                                ->title('Data GCV')
                                ->body('Data GCV berhasil dihapus.'))                        
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),
                
                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle') 
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data GCV')
                                ->body('Data GCV berhasil dihapus secara permanen.'))                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->forceDelete()),
                
                    BulkAction::make('export')
                        ->label('Download Data')
                        ->icon('heroicon-o-arrow-down-tray') 
                        ->color('info')
                        ->action(fn (Collection $records) => static::exportData($records)),
                
                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Kembalikan Data')
                        ->icon('heroicon-o-arrow-path') 
                        ->color('success')
                        ->button()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data GCV')
                                ->body('Data GCV berhasil dikembalikan.')),
                ]);
                
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function exportData(Collection $records)
    {
        $csvData = "ID, Proyek, Nama Perumahan, Kavling, Siteplan/Blok, Type, Luas Tanah, Status, Tanggal Booking, Nama Konsumen, Agent, Status KPR, Keterangan, User, Tanggal Update\n";
    
        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->proyek}, {$record->nama_perusahaan}, {$record->kavling}, {$record->siteplan}, {$record->type}, {$record->luas_tanah}, {$record->status}, {$record->tanggal_booking}, {$record->nama_konsumen}, {$record->agent}, {$record->kpr_status}, {$record->ket}, {$record->user}, {$record->tanggal_update}\n";
        }
    
        return response()->streamDownload(fn () => print($csvData), 'gcv_booking.csv');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            GCVStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGCVS::route('/'),
            'create' => Pages\CreateGCV::route('/create'),
            'edit' => Pages\EditGCV::route('/{record}/edit'),
        ];
    }
}
