<?php

namespace App\Http\Repositories\User;

use App\Http\Repositories\IBaseRepository;
use Illuminate\Http\Request;

interface IUserOtpRepository extends IBaseRepository
{
    public function create($user_id, $otp);

    public function findOtp($otp, $user_id);
}
