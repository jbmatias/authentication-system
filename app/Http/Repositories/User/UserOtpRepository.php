<?php

namespace App\Http\Repositories\User;

use App\Http\Repositories\BaseRepository;
use App\Http\Repositories\User\IUserRepository;
use Illuminate\Support\Facades\Log;
use App\Models\UserOtp;
use Illuminate\Http\Request;

class UserOtpRepository extends BaseRepository implements IUserOtpRepository
{
    protected $userOtp;

    public function __construct(UserOtp $userOtp)
    {
        $this->userOtp = $userOtp;
    }

    public function create($user_id, $otp) {
        $this->userOtp->create([
            'user_id' => $user_id,
            'user_otp' => $otp
        ]);
    }

    public function findOtp($otp, $user_id) {
        return $this->userOtp->where('user_otp', $otp)->where('user_id', $user_id);
    }
}
