<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\LoginResponse;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;


class AuthController extends Controller
{

    //use RegistersUsers;
    //protected $redirectTo = RouteServiceProvider::HOME;

    public function getLogin()
    {
        return view('auth.login');
    }

    public function postLogin(Request $request, LoginResponse $loginResponse)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($request->only('email', 'password'), $remember)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return $loginResponse->toResponse($request);
    }

    public function logout()
    {
        Auth::logout();
        return redirect(RouteServiceProvider::HOME);
    }

    public function getRegister()
    {
        return view('auth.register');
    }

    protected function registered(Request $request, $user)
    {
        event(new Registered($user));
        return redirect('/')->with('success', 'Registration successful. Please check your email for a verification link.');
    }

    public function postRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            //return response()->json($validator->errors(), 422);
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verification_token' => Str::random(40),
        ]);

        //Mail::to($user->email)->send(new WelcomeEmail($user));
        //event(new Registered($user));
        Mail::send('emails.verify', ['user' => $user], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Verify your email address');
        });

        //Auth::login($user);

        //return redirect(RouteServiceProvider::HOME);
        return redirect()->route('login')->with('success', 'Please check your email to verify your account.');
    }

    public function verify()
    {
        return view('auth.verify-email');
    }

    public function verifyToken($token)
    {
        $user = User::where('email_verification_token', $token)->firstOrFail();

        $user->email_verified_at = now();
        $user->email_verification_token = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Your email has been verified. You can now log in.');
    }


    private function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }


    public function getPassword()
    {
        return view('auth.password');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('home')
            ->with('success', 'Your password has been changed.');
    }

    public function getReset(Request $request)
    {
        return view('auth.resetpassword', compact('request'));
    }

    public function postReset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $reset_password_status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($reset_password_status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', trans('passwords.reset'));
        } else {
            return back()->withErrors(['email' => [trans($reset_password_status)]]);
        }
    }

    public function getForgot(Request $request)
    {
        return view('auth.forgotpassword');
    }

    public function postForgot(Request $request)
    {

        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', trans($status))
            : back()->withErrors(['email' => trans($status)]);
    }

    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', "Verification link sent to your email {$request->user()->email}!");
    }
}
