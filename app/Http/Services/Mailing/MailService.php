<?php

namespace App\Http\Services\Mailing;

use Mail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Repositories\User\IUserOtpRepository;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class MailService implements IMailService {

    private $userOtp;
    private $username;

    public function __construct(
        IUserOtpRepository $userOtp)
    {
        $this->userOtp = $userOtp;
        $this->username = config('mail.mailers.smtp.username');
    }
    public function send(Request $recepient): JsonResponse {

        $data = [
            'name' => $recepient->sender,
            'body' => $recepient->body,
            'link' => URL('/api/register')
        ];

        $username = $this->username;

        Mail::send('emails.mail', $data , function ($m) use ($recepient) {
            $m->from($this->username, 'Invitation');
            $m->to($recepient->email, $recepient)->subject('Join us!');
        });

        return response()->json(['message' => 'invitation sent']);
    }

    public function OTP($user, $otp): JsonResponse {

        $this->userOtp->create($user->id, $otp);

        $data = [
            'name' => $user->name,
            'body' => 'Here\'s your OTP code '. $otp,
        ];

        Mail::send('emails.otp', $data , function ($m) use ($user) {
            $m->from($this->username, 'OTP');
            $m->to($user->email, $user)->subject('Confirm your account!');
        });

        return response()->json([
            'message' => 'OTP code has been sent to your email.'
        ]);
    }

    public function validate($otp, $user_id) {
        $userOtp = $this->userOtp->findOtp($otp, $user_id)->first();
        if($userOtp) {
            if(!$userOtp->expired) {
                $userOtp->expired = 1;
                $userOtp->save();

                return response()->json([
                    'message' => 'User has been successfully verified!',
                    'user' => $userOtp->user
                ]);
            }

            return response()->json([
                'message' => 'User OTP expired!'
            ], 400);
        }

        return response()->json([
            'message' => 'User failed to verify'
        ], 400);

    }
}
