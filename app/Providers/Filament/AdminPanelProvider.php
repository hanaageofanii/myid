<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Facades\Filament;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;
use App\Filament\Resources\GCVResource\Widgets\GCVStats;
use App\Filament\Resources\RekonsilResource\Widgets\rekonsilStats;



class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->darkMode(false)
            ->registration()
            ->brandName('PT. Purnama Karya Bersama')
            ->colors([
                'primary' => Color::Amber,
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'success' => Color::Green,
                'warning' => COlor::Red,
            ])->font('Poppins')
            ->brandLogo(request()->is('admin/login') ? asset('image/logo.png') : asset('image/logo-pkb.png'))
            ->brandLogoHeight(request()->is('admin/login') ? '10rem' : '15rem')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->plugin(
                FilamentFullCalendarPlugin::make()
            )
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                // \App\Filament\Widgets\CalendarWidget::class,
                \App\Filament\Resources\AuditResource\Widgets\AuditStats::class,
                \App\Filament\Resources\GCVResource\Widgets\GCVStats::class, 
                \App\Filament\Resources\FormKprResource\Widgets\KPRStats::class,
                \App\Filament\Resources\FormLegalResource\Widgets\SertifikatStats::class,
                \App\Filament\Resources\FormPajakResource\Widgets\PajakStats::class,
                \App\Filament\Resources\FormPpnResource\Widgets\PPNStats::class,
                \App\Filament\Resources\PencairanAkadResource\Widgets\AkadStats::class,
                \App\Filament\Resources\AjbResource\Widgets\AjbStats::class,                \App\Filament\Resources\VerifikasiDajamResource\Widgets\verifikasiDajamStats::class,
                \App\Filament\Resources\PengajuanDajamResource\Widgets\PenDajamStats::class,
                \App\Filament\Resources\PencairanDajamResource\Widgets\pencairanDajamStats::class,
                \App\Filament\Resources\RekonsilResource\Widgets\rekonsilStats::class,
                \App\Filament\Resources\FormDpResource\Widgets\DPStats::class,
                \App\Filament\Resources\RekeningKoranResource\Widgets\rekeningkoranStats::class,
                \App\Filament\Resources\CekPerjalananResource\Widgets\cek_perjalananStats::class,
                \App\Filament\Resources\FormPencocokanResource\Widgets\FormPencocokanStats::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])->favicon(asset('image/logo.png'));
    }
    
}
