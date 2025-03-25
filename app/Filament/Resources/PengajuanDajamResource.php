<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanDajamResource\Pages;
use App\Filament\Resources\PengajuanDajamResource\RelationManagers;
use App\Models\pengajuan_dajam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\verifikasi_dajam;
use App\Models\VerifikasiDajam;
use App\Models\Dajam;
use App\Models\form_kpr;
use App\Models\form_pajak;
use App\Models\PencairanAkad;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Models\form_dp;
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
use App\Filament\Resources\GCVResource;
use App\Models\GCV;
use App\Filament\Resources\KPRStats;


class PengajuanDajamResource extends Resource
{
    protected static ?string $model = pengajuan_dajam::class;

    protected static ?string $title = "Form Pengajuan Dajam";
    protected static ?string $navigationGroup = "Legal";
    protected static ?string $pluralLabel = "Data Pengajuan Dajam";
    protected static ?string $navigationLabel = "Pengajuan Dajam";
    protected static ?string $pluralModelLabel = 'Daftar Pengajuan Dajam';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
            'index' => Pages\ListPengajuanDajams::route('/'),
            'create' => Pages\CreatePengajuanDajam::route('/create'),
            'edit' => Pages\EditPengajuanDajam::route('/{record}/edit'),
        ];
    }
}
