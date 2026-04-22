<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Models\User;
use App\Mail\SecondEmailVerifyMailManager;
use App\Utility\SmsUtility;
use Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */

    public function sendResetLinkEmail(Request $request)
    {
        $phone = $request->has('country_code') && $request->has('phone')
            ? "+{$request['country_code']}{$request['phone']}"
            : null;

        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $request->email)->first();
        } elseif ($phone) {
            $user = User::where('phone', $phone)->first();
        } else {
            flash(translate('No account exists!'))->error();
            return back();
        }

        if (!$user) {
            flash(translate('No account exists with this email/phone'))->error();
            return back();
        }

        $user->verification_code = rand(100000, 999999);
        $user->save();

        if (isset($user->email) && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            $array = [
                'view' => 'emails.verification',
                'from' => env('MAIL_FROM_ADDRESS'),
                'subject' => translate('Password Reset'),
                'content' => translate('Verification Code is') . ': ' . $user->verification_code,
                'type' => 'forgot_code'
            ];

            Mail::to($user->email)->queue(new SecondEmailVerifyMailManager($array));
            flash(__('Verification code sent to your email.'))->success();
            return view('auth.' . get_setting('authentication_layout_select') . '.reset_password');
        } elseif ($phone) {
            SmsUtility::password_reset($user);
            return view('otp_systems.frontend.auth.' . get_setting('authentication_layout_select') . '.reset_with_phone');
        }
    }
}
