<?php

namespace App\Filament\Resources\GcvDataSiteplanResource\Widgets;

use App\Models\GcvDataSiteplan;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class GcvDataSiteplanStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected static ?string $maxHeight = '300px';

    protected static ?string $pollingInterval = null;

    protected static bool $isLazy = false;

    protected ?string $heading = 'Dashboard Statistik GCV Data Siteplan';

    protected function getStats(): array
    {
        $total = GcvDataSiteplan::count();
        $terbangun = GcvDataSiteplan::where('terbangun', true)->count();
        $belumTerbangun = GcvDataSiteplan::where('terbangun', false)->count();

        $kavlingLabels = [
            'standar' => 'Standar',
            'khusus' => 'Khusus',
            'hook' => 'Hook',
            'komersil' => 'Komersil',
            'tanah_lebih' => 'Tanah Lebih',
            'kios' => 'Kios',
        ];

        $counts = GcvDataSiteplan::selectRaw('kavling, COUNT(*) as total')
            ->groupBy('kavling')
            ->pluck('total', 'kavling')
            ->toArray();

        $cards = [
            Card::make('Total Unit', $total)->color('primary'),
            Card::make('Terbangun', $terbangun)->color('success'),
            Card::make('Belum Terbangun', $belumTerbangun)->color('danger'),
        ];

        foreach ($kavlingLabels as $key => $label) {
            $cards[] = Card::make($label, $counts[$key] ?? 0)->color('secondary');
        }

        return $cards;
    }
}
