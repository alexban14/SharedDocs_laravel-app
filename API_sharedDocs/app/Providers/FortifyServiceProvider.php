<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // custom fortify auth
        // Fortify::authenticateUsing(function (Request $request){
            //            $user = User::find(1);
            //            return $user;
            //            // grab credentials from request
            //
            //            // lookup user from db
            //
            //            // verify credentials
            //
            //            // return user model if correct
            //        });

            //         Fortify::authenticateThrough(function (Request $request){
            //             return array_filter([
            //                 config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
            //                 Features::enabled(Features::twoFactorAuthentication()) ? RedirectIfTwoFactorAuthenticatable::class : null,
            // //                DummyDummy::class,
            // //                DummyDummy::class,
            //                 AttemptToAuthenticate::class,
            //                 PrepareAuthenticatedSession::class,
            //             ]);
            //         });

            //        Fortify::confirmPasswordsUsing(function ($user, $password){
            //            // for the confirm password endpoint
            //            // return true if password is correct
            //            // return false if password input is wrong
            //        });

                    // Fortify::confirmPasswordView(function (){
                    //     return view('some.view.in.your.app');
                    // });
                    // Fortify::verifyEmailView(function (){
                    //     return view('auth.verify');
                    // });
                    // Fortify::loginView(fn () => view('some.view.in.your.app'));
                    // Fortify::registerView(fn () => view('some.view.in.your.app'));
                    // Fortify::twoFactorChallengeView(fn () => view('some.view.in.your.app'));
                    // Fortify::requestPasswordResetLinkView(fn () => view('some.view.in.your.app'));
                    // Fortify::resetPasswordView(fn () => view('some.view.in.your.app'));
    }
}
