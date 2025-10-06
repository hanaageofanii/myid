<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvDataSiteplanResource\Pages;
use App\Filament\Resources\GcvDataSiteplanResource\RelationManagers;
use App\Models\GcvDataSiteplan;
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
use Illuminate\Validation\Rule;
use App\Filament\Resources\GcvDataSiteplanResource\Widgets\gcvDataSiteplanStats;



class GcvDataSiteplanResource extends Resource
{
    protected static ?string $model = GcvDataSiteplan::class;
    protected static ?string $title = "Data Siteplan";
    protected static ?string $navigationGroup = "GCV";
    protected static ?string $pluralLabel = "Data Siteplan";
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?string $navigationLabel = 'Legal > Data Siteplan';
    protected static ?string $pluralModelLabel = 'Data Siteplan';

    protected static ?int $navigationSort = 1;

     protected static bool $isScopedToTenant = true;
      protected static ?string $tenantOwnershipRelationshipName = 'team';

    protected static ?string $tenantRelationshipName = 'team';

    public static function form(Form $form): Form
    {
 return $form
        ->columns(1)
        ->extraAttributes(['class' => 'centered-container'])
        ->schema([
           Wizard::make([
            Wizard\Step::make('Informasi')
                ->description('Data utama terkait Siteplan')
                ->schema([
                    Section::make('Informasi')
                        ->schema([
                            TextInput::make('siteplan')
                                ->label('Site Plan')
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
                        ])
                            ->columns(2),
                        ]),

            Wizard\Step::make('Detail Unit')
            ->description('Informasi spesifikasi unit')
                ->schema([
                    Section::make('Detail Unit')

                        ->schema([
                            Select::make('kavling')
                                ->label('Jenis Unit')
                                ->required()
                                ->options([
                                    'standar' => 'Standar',
                                    'khusus' => 'Khusus',
                                    'hook' => 'Hook',
                                    'komersil' => 'Komersil',
                                    'tanah_lebih' => 'Tanah Lebih',
                                    'kios' => 'Kios',
                                ])
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
                                ->label('Luas')
                                ->required()
                                ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                 return $user && $user->hasRole(['admin','Legal officer']);
                                })()),
                        ])
                        ->columns(3),
                ]),

            Wizard\Step::make('Keterangan Tambahan')
                ->schema([
                    Section::make('Keterangan Tambahan')
                        ->schema([
                            Textarea::make('keterangan')
                                ->label('Keterangan')
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
                Tables\Columns\TextColumn::make('siteplan')->label('Blok')->searchable(),
                Tables\Columns\TextColumn::make('kavling')->label('Kavling')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'standar' => 'Standar',
                    'khusus' => 'Khusus',
                    'hook' => 'Hook',
                    'komersil' => 'Komersil',
                    'tanah_lebih' => 'Tanah Lebih',
                    'kios' => 'Kios',
                    default => $state,
                })->searchable(),
                TextColumn::make('type')->label('Type')->searchable(),
                TextColumn::make('luas')->label('Luas')->searchable(),
                BooleanColumn::make('terbangun')->label('Terbangun')->searchable(),
                TextColumn::make('keterangan')->label('Keterangan')->searchable(),
            ])
            ->defaultSort('siteplan', 'asc')
            ->headerActions([
                Action::make('count')
                    ->label(fn ($livewire): string => 'Total: ' . $livewire->getFilteredTableQuery()->count())
                    ->disabled(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
                ->label('Data yang dihapus')
                ->native(false),

                Filter::make('kavling')
                    ->label('Kavling')
                    ->form([

                Select::make('kavling')
                    ->options([
                        'standar' => 'Standar',
                        'khusus' => 'Khusus',
                        'hook' => 'Hook',
                        'komersil' => 'Komersil',
                        'tanah_lebih' => 'Tanah Lebih',
                        'kios' => 'Kios',
                    ])
                    ->nullable()
                    ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['kavling']), fn ($q) =>
                            $q->where('kavling', $data['kavling'])
                        )
                    ),

                Filter::make('terbangun')
                    ->label('Terbangun')
                    ->form([

                Select::make('terbangun')
                    ->options([
                        '1' => 'Sudah Terbangun',
                        '0' => 'Belum Terbangun',
                    ])
                    ->nullable()
                    ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['terbangun']), fn ($q) =>
                            $q->where('terbangun', $data['terbangun'])
                        )
                    ),

                Filter::make('created_from')
                    ->label('Dari Tanggal')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Dari')
                            ->displayFormat('Y-m-d'), // <- Tambah ini
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
                            ->displayFormat('Y-m-d'), // <- Tambah ini juga
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when($data['created_until'] ?? null, fn ($q) =>
                            $q->whereDate('created_at', '<=', $data['created_until'])
                        )
                    ),

            ], layout: FiltersLayout::AboveContent)
            ->filtersFormMaxHeight('400px')
            ->filtersFormColumns(5)
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
                                ->title('Data Siteplan Diperbarui')
                                ->body('Data Siteplan telah berhasil disimpan.')),
                    DeleteAction::make()
                                ->color('danger')
                                ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                                ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok {$record->siteplan}")
                                ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                                ->successNotification(
                                    Notification::make()
                                        ->success()
                                        ->title('Data Siteplan Dihapus')
                                        ->body('Data Siteplan telah berhasil dihapus.')),
                    // RestoreAction::make()
                    //     ->label('Pulihkan')
                    //     ->successNotificationTitle('Data berhasil dipulihkan')
                    //     ->successRedirectUrl(route('filament.admin.resources.Siteplans.index')),
                    Tables\Actions\RestoreAction::make()
                    ->color('info')
                    ->label(fn ($record) => "Kembalikan {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Kembalikan Blok{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengembalikan blok {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Siteplan')
                            ->body('Data Siteplan berhasil dikembalikan.')
                    ),
                    Tables\Actions\ForceDeleteAction::make()
                    ->color('primary')
                    ->label(fn ($record) => "Hapus Permanent {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok Permanent{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus blok secara permanent {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Siteplan')
                            ->body('Data Siteplan berhasil dihapus secara permanen.')
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
                                ->title('Data Siteplan')
                                ->body('Data Siteplan berhasil dihapus.'))
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),

                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Siteplan')
                                ->body('Data Siteplan berhasil dihapus secara permanen.'))
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->forceDelete()),

                    BulkAction::make('export')
                        ->label('Download Data')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('info')
                        ->action(fn (Collection $records) => static::exportData($records)),

                    BulkAction::make('print')
                    ->label('Print Data')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->action(function (Collection $records){
                        session(['print_records' => $records->pluck('id')->toArray()]);
                        return redirect()->route('datasiteplan.print');
                    }),

                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Kembalikan Data')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->button()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Siteplan')
                                ->body('Data Siteplan berhasil dikembalikan.')),
                ]);

    }

    public static function exportData(Collection $records)
    {
        $csvData = "ID, Site Plan, Kavling, Type, Luas, Terbangun, Keterangan\n";

        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->siteplan}, {$record->kavling}, {$record->type}, {$record->luas}, {$record->terbangun}, {$record->keterangan}\n";
        }

        return response()->streamDownload(fn () => print($csvData), 'DataSiteplan.csv');
    }


    public static function getRelations(): array
    {
        return [

        ];
    }

public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ])
        ->where('team_id', filament()->getTenant()->id); // filter data sesuai tenant
}
        public static function getWidgets(): array
        {
            return [
                GcvDataSiteplanStats::class,
            ];
        }




    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGcvDataSiteplans::route('/'),
            'create' => Pages\CreateGcvDataSiteplan::route('/create'),
            'edit' => Pages\EditGcvDataSiteplan::route('/{record}/edit'),
        ];
    }
}
