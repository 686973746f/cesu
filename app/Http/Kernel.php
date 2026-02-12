<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'isAdmin' => \App\Http\Middleware\SuperAdminMiddleware::class,
        'isAccountEnabled' => \App\Http\Middleware\isAccountEnabledMiddleware::class,
        'isCesuAccount' => \App\Http\Middleware\isCesuAccount::class,
        'isCesuOrBrgyAccount' => \App\Http\Middleware\isCesuOrBrgyAccount::class,
        'isLevel1' => \App\Http\Middleware\isLevel1::class,
        'isLevel2' => \App\Http\Middleware\isLevel2::class,
        'isLevel3' => \App\Http\Middleware\isLevel3::class,
        'canAccessCovid' => \App\Http\Middleware\canAccessCovid::class,
        'canAccessAbtc' => \App\Http\Middleware\canAccessAbtc::class,
        'canAccessVaxcert' => \App\Http\Middleware\canAccessVaxcert::class,
        'canAccessPidsr' => \App\Http\Middleware\canAccessPidsr::class,
        'canAccessPidsrAdminMode' => \App\Http\Middleware\canAccessPidsrAdminMode::class,
        'canAccessSyndromic' => \App\Http\Middleware\canAccessSyndromic::class,
        'canAccessFhsis' => \App\Http\Middleware\canAccessFhsis::class,
        'canAccessPharmacy' => \App\Http\Middleware\canAccessPharmacy::class,
        'canAccessPharmacyMasterAdmin' => \App\Http\Middleware\canAccessPharmacyMasterAdmin::class,
        'canAccessPharmacyBranchAdmin' => \App\Http\Middleware\canAccessPharmacyBranchAdmin::class,
        'canAccessPharmacyBranchAdminOrMasterAdmin' => \App\Http\Middleware\canAccessPharmacyBranchAdminOrMasterAdmin::class,
        'canAccessPharmacyBranchEncoder' => \App\Http\Middleware\canAccessPharmacyBranchEncoder::class,
        'canAccessFwri' => \App\Http\Middleware\canAccessFwri::class,
        'canAccessPregnancyTracking' => \App\Http\Middleware\canAccessPregnancyTracking::class,
        'canAccessQes' => \App\Http\Middleware\canAccessQes::class,
        'isGlobalAdmin' => \App\Http\Middleware\isGlobalAdmin::class,
        'isLoggedInEdcsBrgyPortal' => \App\Http\Middleware\isLoggedInEdcsBrgyPortal::class,
        'isMayor' => \App\Http\Middleware\isMayor::class,
        'canAccessTask' => \App\Http\Middleware\canAccessTask::class,
        'canAccessDisaster' => \App\Http\Middleware\canAccessDisaster::class,
        'canAccessEmployees' => \App\Http\Middleware\canAccessEmployees::class,
        'canAccessAbtcInventory' => \App\Http\Middleware\canAccessAbtcInventory::class,
        'canAccessNonComm' => \App\Http\Middleware\canAccessNonComm::class,
        'school' => \App\Http\Middleware\SchoolAuth::class,
        'school_admin' => \App\Http\Middleware\SchoolAdmin::class,
        'canAccessElectronicTcl' => \App\Http\Middleware\canAccessElectronicTcl::class,
        'canAccessFhsisOrElectronicTcl' => \App\Http\Middleware\canAccessFhsisOrElectronicTcl::class,
        'canAccessOpdPatients' => \App\Http\Middleware\canAccessOpdPatients::class,
        'hasLatestPassword' => \App\Http\Middleware\HasLatestPassword::class,
    ];
}
