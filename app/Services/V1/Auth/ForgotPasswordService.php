<?php

namespace App\Services\V1\Auth;

use App\Contracts\Abstracts\Services\BaseService;
use App\Exceptions\ForbiddenActionException;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Iqbalatma\LaravelServiceRepo\Exceptions\EmptyDataException;

class ForgotPasswordService extends BaseService
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }


    /**
     * @param array $requestedData
     * @return void
     * @throws EmptyDataException|ForbiddenActionException
     */
    public function request(array $requestedData): void
    {
        $user = UserRepository::getSingleByEmail($requestedData["email"]);
        if (!$user) {
            throw new EmptyDataException();
        }

        $status = Password::sendResetLink(
            $requestedData
        );

        if ($status !== Password::RESET_LINK_SENT) {
            throw new ForbiddenActionException(__($status));
        }
    }


    /**
     * @param array $requestedData
     * @return void
     * @throws ForbiddenActionException
     */
    public function reset(array $requestedData):void
    {
        $status = Password::reset(
            $requestedData,
            static function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET){
            throw new ForbiddenActionException(__($status));
        }
    }
}
