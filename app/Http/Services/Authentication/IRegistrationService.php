<?php

namespace App\Http\Services\Authentication;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

interface IRegistrationService {

    public function registerAdmin(Request $recepient) : JsonResponse;

    public function register(Request $recepient) : JsonResponse;

    public function findByEmail($email);

    public function validateOTP($otp, $user_id);

}
