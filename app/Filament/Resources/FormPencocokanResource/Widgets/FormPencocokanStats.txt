<?php

namespace App\Filament\Resources\FormPencocokanResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\form_pencocokan;
use Filament\Widgets\StatsOverviewWidget\Card;

use Illuminate\Support\Carbon;

class FormPencocokanStats extends BaseWidget
{

    protected static ?int $sort = 18;
    protected static ?string $maxHeight = '300px';
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = false;
    protected ?string $heading = 'Dashboard Data Pencocokan';

    protected function getCards(): array
    {
        return [
            Card::make('Total Transaksi', number_format(form_pencocokan::count()))
            ->description('Jumlah seluruh transaksi')
            ->color('primary'),
    
        Card::make('Total Jumlah Dana (Rp)', 'Rp. ' . number_format(form_pencocokan::sum('jumlah')))
            ->description('Akumulasi dana dari semua transaksi')
            ->color('success'),
    
        Card::make('Transaksi Sudah Disalurkan', number_format(form_pencocokan::where('status_disalurkan', 'sudah')->count()))
            ->description('Dana yang sudah disalurkan')
            ->color('success'),
    
        Card::make('Transaksi Belum Disalurkan', number_format(form_pencocokan::where('status_disalurkan', 'belum')->count()))
            ->description('Dana yang belum disalurkan')
            ->color('warning'),
    
        Card::make('Transaksi dengan Selisih Nominal', number_format(form_pencocokan::where('nominal_selisih', '!=', 0)->count()))
            ->description('Terdapat perbedaan jumlah nominal')
            ->color('danger'),
    
        Card::make('Transaksi Sudah Divalidasi', number_format(form_pencocokan::whereNotNull('tanggal_validasi')->count()))
            ->description('Sudah melalui proses validasi')
            ->color('info'),
    
        Card::make('Transaksi Disetujui', number_format(form_pencocokan::where('status', 'approve')->count()))
            ->description('Transaksi yang diapprove oleh tim')
            ->color('success'),
    
        Card::make('Transaksi Direvisi', number_format(form_pencocokan::where('status', 'revisi')->count()))
            ->description('Transaksi yang perlu direvisi')
            ->color('warning'),
    
        Card::make('Transaksi Bertindak Koreksi', number_format(form_pencocokan::where('tindakan', 'koreksi')->count()))
            ->description('Tindakan koreksi pada transaksi')
            ->color('danger'),
    
        Card::make('Transaksi Ditandai Pending', number_format(form_pencocokan::where('tindakan', 'pending')->count()))
            ->description('Masih menunggu tindakan lanjutan')
            ->color('warning'),
    
        Card::make('Transaksi Diabaikan', number_format(form_pencocokan::where('tindakan', 'abaikan')->count()))
            ->description('Transaksi yang diabaikan')
            ->color('gray'),
        ];
    }
    // public static function canView(): bool
    //     {
    //         return auth()->user()->role === ['admin','Direksi','Super admin','Legal Pajak','Legal officer','KPR Stok','KPR officer','Kasir 1','Kasir 2'];
    //     }
}
