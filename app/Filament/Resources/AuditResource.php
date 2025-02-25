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

class AuditResource extends Resource
{
    protected static ?string $model = Audit::class;

    protected static ?string $title = "Audit";
    protected static ?string $navigationGroup = "Legal";
    protected static ?string $pluralLabel = "Audit";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Audit';
    protected static ?string $pluralModelLabel = 'Daftar Audit';

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
                ->default(false)
                ->onColor('success')
                ->offColor('danger'),

            Select::make('status')
                ->label('Status')
                ->options([
                    'akad' => 'Akad',
                ])
                ->required()->nullable()->native(false),

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
                ->columns(4),

            // Grup TANDA TERIMA
            Fieldset::make('Berkas Lainnya')
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
        return $table
        ->columns([
            TextColumn::make('siteplan')->label('Site Plan'),
            TextColumn::make('type')->label('Type')->searchable(),
            BooleanColumn::make('terbangun')->label('Terbangun'),
            TextColumn::make('status')->label('Status')->badge(),
            TextColumn::make('tanda_terima_sertifikat')->label('Tanda Terima Sertifikat'),
            TextColumn::make('kode1')->label('1'),
            TextColumn::make('luas1')->label('Luas (m²)'),
            TextColumn::make('kode2')->label('2'),
            TextColumn::make('luas2')->label('Luas (m²)'),
            TextColumn::make('kode3')->label('3'),
            TextColumn::make('luas3')->label('Luas (m²)'),
            TextColumn::make('kode4')->label('4'),
            TextColumn::make('luas4')->label('Luas (m²)'),
            TextColumn::make('nop_pbb_pecahan')->label('NOP / PBB Pecahan')->limit(20),
            TextColumn::make('tanda_terima_nop')->label('Tanda Terima NOP')->limit(20),
            TextColumn::make('imb_pbg')->label('IMB / PBG')->limit(20),
            TextColumn::make('tanda_terima_imb_pbg')->label('Tanda Terima IMB/PBG')->limit(20),
            TextColumn::make('tanda_terima_tambahan')->label('Tanda Terima Tambahan')->limit(50),
            ])
            ->defaultSort('siteplan', 'asc')
            ->headerActions([
                Action::make('count')
                    ->label(fn ($livewire): string => 'Total: ' . $livewire->getFilteredTableQuery()->count())
                    ->disabled(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
                ->label('Data yang dihapus'),
                Filter::make('status')
                    ->label('Status')
                    ->form([
                        Select::make('status')
                            ->options([
                                'akad' => 'Akad',
                            ])
                            ->nullable()
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['status']), fn ($q) =>
                            $q->where('status', $data['status'])
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
                        ->label('Lihat'),
                    EditAction::make()
                        ->label('Ubah')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Audit Diperbarui')
                                ->body('Data Audit telah berhasil disimpan.')),                    
                        DeleteAction::make()
                        ->label('Hapus')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Audit Dihapus')
                                ->body('Data Audit telah berhasil dihapus.')),
                    // RestoreAction::make()
                    //     ->label('Pulihkan')
                    //     ->successNotificationTitle('Data berhasil dipulihkan')
                    //     ->successRedirectUrl(route('filament.admin.resources.audits.index')),
                    Tables\Actions\RestoreAction::make()
                    ->label('Kembalikan Data')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Audit')
                            ->body('Data Audit berhasil dikembalikan.')
                    ),
                    Tables\Actions\ForceDeleteAction::make()
                    ->label('Hapus Permanen')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Audit')
                            ->body('Data Audit berhasil dihapus secara permanen.')
                    ),
                    ])->button()->label('Action'),
                ], position: ActionsPosition::BeforeCells)
            
            ->groupedBulkActions([
                BulkAction::make('delete')
                ->label('Hapus')
                ->button()
                ->color('danger')
                    ->successNotificationTitle('Data berhasil di hapus')
                    ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each->delete()),
                
                BulkAction::make('forceDelete')
                ->label('Hapus Permanent')
                ->button()
                ->color('Warning')
                    ->successNotificationTitle('Data Berhasil dihapus permanent')
                    ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each->forceDelete()),
                
                // RestoreBulkAction::make()
                // ->label('Pulihkan Data')
                // ->button()
                // ->color('success')
                //     ->successNotificationTitle('Data berhasil dipulihkan')
                //     ->successRedirectUrl(route('filament.admin.resources.audits.index')),

                BulkAction::make('export')
                    ->label('Download Data')
                    ->color('info')
                    ->button()
                    ->action(fn (Collection $records) => static::exportData($records)),

                Tables\Actions\RestoreBulkAction::make()
                ->label('Kambalikan Data')
                ->color('indigo')
                ->button()
            ]);
    }
    
    public static function exportData(Collection $records)
    {
        $csvData = "ID, Site Plan, Type, Terbangun, Status, Tanda Terima Sertifikat, 1, Luas, 2, Luas, 3, Luas, 4, Luas, NOP / PBB Pecahan, Tanda Terima NOP, IMB / PBG, Tanda Terima IMB/PBG, Tanda Terima Tambahan\n";
    
        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->siteplan}, {$record->type}, {$record->terbangun}, {$record->status}, {$record->tanda_terima_sertifikat}, {$record->kode1}, {$record->luas1}, {$record->kode2}, {$record->luas2}, {$record->kode3}, {$record->luas3}, {$record->kode4}, {$record->luas4}, {$record->nop_pbb_pecahan}, {$record->tanda_terima_nop}, {$record->imb_pbg}, {$record->tanda_terima_imb_pbg}, {$record->tanda_terima_tambahan}\n";
        }
    
        return response()->streamDownload(fn () => print($csvData), 'export.csv');
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAudits::route('/'),
            'create' => Pages\CreateAudit::route('/create'),
            // 'edit' => Pages\EditAudit::route('/{record}/edit'),
        ];
    }
}