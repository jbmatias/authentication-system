<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Http\Services\Authentication\IRegistrationService;
use App\Http\Services\Mailing\IMailService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail;

class AdminController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $mail;
    private $registration;

    public function __construct(
        IMailService $mailService,
        IRegistrationService $registrationService) {
            $this->mail = $mailService;
            $this->registration = $registrationService;
    }

    public function sendInvite(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'body' => 'required|string|min:10|max:1000',
            'link' => URL('/register')
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        return $this->mail->send($request);

    }


    public function registerAdmin(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'avatar' => [
                'required',
                Rule::dimensions()->maxWidth(256)->maxHeight(256),
            ],
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        return $this->registration->registerAdmin($request);
    }

    public function registerUser(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'avatar' => [
                'required',
                Rule::dimensions()->maxWidth(256)->maxHeight(256),
            ],
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        return $this->registration->register($request);
    }

    public function confirmOtp(Request $request) {

        $validator = Validator::make($request->all(), [
            'otp' => 'required|string|min:6|max:6',
            'email' => 'required|exists:users'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        return $this->registration->validateOTP($request->otp, $this->registration->findByEmail($request->email)->id);
    }

    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addYears(1);
        }

        $token->save();

        $response = [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
        ];

        return response()->json(array_merge($response, $user->toArray()));
    }
}
