<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
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

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();
     
            if ($user &&
                Hash::check($request->password, $user->password)) {
                    if(isset($user->roles[0])){
                        $rol = $user->roles[0];
                        switch ($rol->id) {
                            case '1':
                                    session(['userRol' => $rol]);
                                break;
                            default:
                                    if(isset($user->companies[0])){
                                        session(['userRol' => $rol]);
                                    }else{
                                        throw ValidationException::withMessages([
                                            Fortify::username() => ['El usuario no se encuentra asignado a una empresa | Por favor comuniquese con el admnistrador'],
                                        ]);
                                    }
                                break;
                        }
                    }else{
                        throw ValidationException::withMessages([
                            Fortify::username() => ['El usuario no posee un rol de acceso válido | Por favor comuniquese con el admnistrador'],
                        ]);
                    }
                return $user;
            }
        });
    }
}
