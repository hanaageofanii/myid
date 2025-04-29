<?php

namespace App\Providers;
use App\Models\User;
use App\Models\ajb;
use App\Models\Audit;
use App\Models\dajam;
use App\Models\form_dp;
use App\Models\form_kpr;
use App\Models\form_legal;
use App\Models\form_pajak;
use App\Models\form_ppn;
use App\Models\GCV;
use App\Models\pembayaran;
use App\Models\pencairan_dajam;
use App\Models\PencairanAkad;
use App\Models\pengajuan_dajam;
use App\Models\verifikasi_dajam;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\ajbPolicy;
use App\Policies\AuditPolicy;
use App\Policies\dajamPolicy;
use App\Policies\form_dpPolicy;
use App\Policies\form_kprPolicy;
use App\Policies\form_legalPolicy;
use App\Policies\form_pajakPolicy;
use App\Policies\form_ppnPolicy;
use App\Policies\GCVPolicy;
use App\Policies\pembayaranPolicy;
use App\Policies\pencairan_dajamPolicy;
use App\Policies\PencairanAkadPolicy;
use App\Policies\pengajuan_dajamPolicy;
use App\Policies\verifikasi_dajamPolicy;
use App\Policies\UserPolicy;


// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
        ajb::class => ajbPolicy::class,
        Audit::class => AuditPolicy::class,
        // dajam::class => dajamPolicy::class,
        form_dp::class => form_dpPolicy::class,
        form_kpr::class => form_kprPolicy::class,
        form_legal::class => form_legalPolicy::class,
        form_pajak::class => form_pajakPolicy::class,
        form_ppn::class => form_ppnPolicy::class,
        GCV::class => GCVPolicy::class,
        // pembayaran::class => pembayaranPolicy::class,
        pencairan_dajam::class => pencairan_dajamPolicy::class,
        PencairanAkad::class => PencairanAkadPolicy::class,
        pengajuan_dajam::class => pengajuan_dajamPolicy::class,
        verifikasi_dajam::class => verifikasi_dajamPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
