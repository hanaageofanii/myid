<?php

namespace App\Providers;
use App\Models\LegalTkr;
use App\Models\PencairanAkadTkr;
use App\Models\User;
use App\Models\ajb;
use App\Models\ajbPCA;
use App\Models\Audit;
use App\Models\audit_tkr;
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
use App\Policies\ajbPCAPolicy;
use App\Policies\form_pajak_pcaPolicy;
use App\Policies\PcaPolicy;
use App\Policies\pengajuan_dajam_pcaPolicy;
use App\Policies\StokTKR;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\ajbPolicy;
use App\Policies\AjbTkrPolicy;
use App\Policies\audit_tkr as PoliciesAudit_tkr;
use App\Policies\AuditPCAPolicy;
use App\Policies\AuditPolicy;
use App\Policies\BukuRekonsilPolicy;
use App\Policies\dajamPolicy;
use App\Policies\form_dpPolicy;
use App\Policies\form_kprPolicy;
use App\Policies\form_legal_pcaPolicy;
use App\Policies\form_legalPolicy;
use App\Policies\form_pajakPolicy;
use App\Policies\form_ppn_pcaPolicy;
use App\Policies\form_ppnPolicy;
use App\Policies\FormDpTkrPolicy;
use App\Policies\FormKprTkrPolicy;
use App\Policies\GCVPolicy;
use App\Policies\PajakTkrPolicy;
use App\Policies\pembayaranPolicy;
use App\Policies\pencairan_dajam_pcaPolicy;
use App\Policies\pencairan_dajamPolicy;
use App\Policies\PencairanAkadPolicy;
use App\Policies\PencairanAkadTkrPolicy;
use App\Policies\pengajuan_dajamPolicy;
use App\Policies\PengajuanDajamTkrPolicy;
use App\Policies\PpnTkrPolicy;
use App\Policies\form_kpr_pcaPolicy;
use App\Policies\StokTKRPolicy;
use App\Policies\RekeningPolicy;
use App\Policies\verifikasi_dajamPolicy;
use App\Policies\UserPolicy;
use App\Policies\VerifikasiDajamTkrPolicy;
use App\Models\BukuRekonsil;
use App\Models\form_dp_pca;
use App\Models\Rekening;
use App\Models\pengajuan_dajam_pca;
use App\Models\form_kpr_pca;
use App\Models\Pca;
use App\Models\form_pajak_pca;
use App\Models\form_ppn_pca;
use App\Models\pencairan_akad_pca;
use App\Models\verifikasi_dajam_pca;
use App\Models\form_legal_pca;
use App\Policies\form_dp_pcaPolicy;
use App\Policies\pencairan_akad_pcaPolicy;
use App\Policies\PencairanDajamTkrPolicy;
use App\Policies\verifikasi_dajam_pcaPolicy;
use App\Models\kas_kecil;
use App\Policies\KasKecilPolicy;
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

];




    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
