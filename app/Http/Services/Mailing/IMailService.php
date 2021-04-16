<?php

namespace App\Http\Services\Mailing;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface IMailService {

    public function send(Request $recepient) : JsonResponse;

    public function OTP($user, $otp) : JsonResponse;

    public function validate($otp, $user_id);

}
