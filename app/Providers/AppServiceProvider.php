<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Http\Services\Mailing\MailService;
use App\Http\Repositories\BaseRepository;
use App\Http\Repositories\User\UserRepository;
use App\Http\Repositories\User\UserOtpRepository;
use App\Http\Services\Authentication\RegistrationService;

use App\Http\Services\Mailing\IMailService;
use App\Http\Repositories\IBaseRepository;
use App\Http\Repositories\User\IUserRepository;
use App\Http\Repositories\User\IUserOtpRepository;
use App\Http\Services\Authentication\IRegistrationService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IMailService::class, MailService::class);
        $this->app->bind(IBaseRepository::class, BaseRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IRegistrationService::class, RegistrationService::class);
        $this->app->bind(IUserOtpRepository::class, UserOtpRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
