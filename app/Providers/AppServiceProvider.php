<?php

namespace App\Providers;

use App\Models\User;
use App\Models\AdminPermission;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        VerifyEmail::toMailUsing(function ($notifiable) {
            $verifyUrl = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                ]
            );

            $user = User::whereEmail($notifiable->email)->first();
            return (new MailMessage)
                ->subject('Please verify your email for Sentegrate registration')
                 ->bcc('ned.siddiqi@sentegrate.com.au')
                ->markdown('emails.verify-email', ['user' => $user, 'verifyUrl' => $verifyUrl]);
        });

        view()->composer('*', function ($view) {
            $permissions=[];

            if(Auth::check() && !empty(Auth::user()->permissions)){
                $permissions=Auth::user()->permissions->pluck('read_permit','module_name')->toArray();
            }
            $view->with(compact( 'permissions'));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
