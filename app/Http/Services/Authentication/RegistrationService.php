<?php

namespace App\Http\Services\Authentication;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Repositories\User\IUserRepository;
use App\Http\Services\Mailing\IMailService;
use Illuminate\Support\Facades\Hash;

class RegistrationService implements IRegistrationService {

    protected $userRepo;
    protected $mail;

    public function __construct(
        IUserRepository $userRepo,
        IMailService $mailService)
    {
        $this->userRepo = $userRepo;
        $this->mail = $mailService;
    }

    public function registerAdmin(Request $request) : JsonResponse {

        $path = $request->file('avatar')->store('avatars');

        $user = $this->userRepo->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'avatar' => $path
        ]);

        return response()->json([
            'message' => 'admin created, please login to get admin token'
        ]);

    }

    public function register(Request $request) : JsonResponse {

        $path = $request->file('avatar')->store('avatars');

        $user = $this->userRepo->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'avatar' => $path
        ]);

        $otp = mt_rand(100000, 999999);

        return $this->mail->OTP($user, $otp);
    }

    public function findByEmail($email) {
        return $this->userRepo->where('email', $email)->first();
    }

    public function validateOTP($otp, $user_id) {
        return $this->mail->validate($otp, $user_id);
    }

}
