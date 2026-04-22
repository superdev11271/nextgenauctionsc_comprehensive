<?php

namespace App\Http\Controllers\Auth;

use App\Models\Upload;
use Nexmo;
use Cookie;
use Session;
use App\Models\Cart;
use App\Models\User;
use Twilio\Rest\Client;

use App\Rules\Recaptcha;
use Illuminate\Validation\Rule;

use App\Models\Customer;
use App\OtpConfiguration;
use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\OTPVerificationController;
use App\Notifications\EmailVerificationNotification;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'first_name'    => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'last_name'     => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'email'         => 'required|string|email|max:255|unique:users',
            'phone'         => 'required|string|max:20|regex:/^[0-9+]+$/',
            'password'     => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',      // Lowercase
                'regex:/[A-Z]/',      // Uppercase
                'regex:/[0-9]/',      // Number
                'regex:/[@$!%*#?&]/', // Special char
                'confirmed'
            ],
            'g-recaptcha-response' => [
                Rule::when(get_setting('google_recaptcha') == 1, ['required', new Recaptcha()], ['sometimes'])
            ],
        ];

        $messages = [
            'first_name.required' => 'First name is required.',
            'first_name.regex'    => 'First name can only contain letters and spaces.',
            'last_name.required'  => 'Last name is required.',
            'last_name.regex'     => 'Last name can only contain letters and spaces.',
            'phone.regex'         => 'Phone number can only contain numbers and "+".',
            'password.regex'       => 'The password must include at least one lowercase letter, one uppercase letter, one number, and one special character.',
            'password.min'         => 'The password must be at least 8 characters long.',
            'password.confirmed'   => 'The password confirmation does not match.',
        ];

        return Validator::make($data, $rules, $messages);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $userData = [
            'user_code' => create_user_code($data['first_name'] . ' ' . $data['last_name']),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'name' => $data['first_name'] . ' ' . $data['last_name'], // Maintain backward compatibility
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ];

        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $user = User::create($userData);
        } else {
            if (addon_is_activated('otp_system')) {
                $userData['phone'] = '+' . $data['country_code'] . $data['phone'];
                $userData['verification_code'] = rand(100000, 999999);
                unset($userData['email']);

                $user = User::create($userData);

                $otpController = new OTPVerificationController;
                $otpController->send_code($user);
            }
        }

        // Handle cart transfer for temp users
        if (session('temp_user_id') != null) {
            Cart::where('temp_user_id', session('temp_user_id'))
                ->update([
                    'user_id' => $user->id,
                    'temp_user_id' => null
                ]);
            Session::forget('temp_user_id');
        }

        // Handle referral code
        if (Cookie::has('referral_code')) {
            $referral_code = Cookie::get('referral_code');
            $referred_by_user = User::where('referral_code', $referral_code)->first();
            if ($referred_by_user != null) {
                $user->referred_by = $referred_by_user->id;
                $user->save();
            }
        }

        return $user;
    }

    public function register(Request $request)
    {
        // Check for existing email/phone
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            if (User::where('email', $request->email)->first() != null) {
                flash(translate('Email already exists.'));
                return back();
            }
        } elseif (User::where('phone', '+' . $request->country_code . $request->phone)->first() != null) {
            flash(translate('Phone already exists.'));
            return back();
        }

        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        if ($user) {
            // Handle ID image upload (your existing logic)
            if ($request->hasFile('id_image')) {
                $image = $request->file('id_image');
                $size = $image->getSize();
                $extension = $image->extension();
                $type = $image->getClientMimeType();
                $originalName = $image->getClientOriginalName();

                $path = public_path() . '/uploads/all/';
                $filename = time() . '.' . $image->getClientOriginalExtension();
                $image->move($path, $filename);

                $original_name = explode('.', $originalName);
                $type = explode('/', $type);
                $upload = new Upload();
                $upload->file_original_name = $original_name[0];
                $upload->file_name = 'uploads/all/' . $filename;
                $upload->file_size = $size;
                $upload->extension = $extension;
                $upload->type = $type[0];
                $upload->save();

                if ($upload->id) {
                    User::where('id', $user->id)->update(['id_photo' => $upload->id]);
                }
            }
        }

        $this->guard()->login($user);

        // Email verification logic (your existing logic)
        if ($user->email != null) {
            if (BusinessSetting::where('type', 'email_verification')->first()->value != 1) {
                $user->email_verified_at = date('Y-m-d H:m:s');
                $user->save();
                offerUserWelcomeCoupon();
                flash(translate('Registration successful.'))->success();
            } else {
                try {
                    $user->verification_code = encrypt($user->user_code);
                    $user->save();
                    $user->sendEmailVerificationNotification();
                    flash(translate('Registration successful. Please verify your email.'))->success();
                } catch (\Throwable $th) {
                    flash(translate($th->getMessage()))->error();
                    flash(translate('Registration failed. Please try again later.'))->error();
                }
            }
        }

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    // Keep your existing registered() method unchanged
    protected function registered(Request $request, $user)
    {
        if ($user->email == null) {
            return redirect()->route('verification');
        } elseif (session('link') != null) {
            return redirect(session('link'));
        } else {
            return redirect()->route('home');
        }
    }
}
