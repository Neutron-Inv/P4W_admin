<?php

namespace App\Http\Controllers\App\Auth;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Core\Auth\User\UserRegisterRequest;
use App\Models\App\Applicant\Applicant;
use App\Models\Core\Auth\Role;
use App\Models\Core\Auth\User;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Carbon;
use App\Notifications\Core\User\UserNotification;
use Illuminate\Http\Request as BaseRequest;
use App\Mail\Core\User\EmailVerificationMail;

class UserRegisterController extends Controller
{


    public function signUpView()
    {
        return view('frontend.user.sign_up');
    }

    public function verifyShow()
    {
        return view('frontend.user.verify_email');
    }

    public function register(UserRegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        // If verification_code is present, this is the verification step
        if ($request->filled('verification_code')) {
            $user = User::where('email', $request->email)
                ->where('verification_code', $request->verification_code)
                ->first();
            if (!$user) {
                return response()->json(['message' => 'Invalid verification code.'], 422);
            }
            $user->email_verified_at = Carbon::now();
            $user->verification_code = null;
            $user->save();
            return response()->json([
                'status' => true,
                'message' => 'Email verified and registration complete!'
            ], 200);
        }

        // Registration step
        $verification_code = rand(100000, 999999);
        $user = User::query()->updateOrCreate(
            ['email' => $request->email,],
            [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' =>  Hash::make($request->password),
                'status_id' => resolve(StatusRepository::class)->userActive(),
                'verification_code' => $verification_code,
                'email_verified_at' => null,
            ]
        );

        $role = Role::query()->where('name', 'Candidate')->first();
        $user->assignRole($role);

        Applicant::query()->updateOrCreate([
            'email' => $request->email,
        ], [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'user_id' => $user->id,
        ]);

        // Send verification code notification
        Mail::to($user)
            ->send(
                (new EmailVerificationMail($user, $verification_code))
                    ->onQueue('default')
                    ->delay(5)
            );

        return response()->json([
            'status' => true,
            'message' => 'Verification code sent to your email. Please verify to complete registration.',
            'email' => $user->email,
            'show_verification' => true
        ]);
    }

    public function verify(BaseRequest $request)
    {
        $request->validate([
            'verification_code' => 'required|min:6',
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw new GeneralException(trans('default.user_not_found'));
        }

        // Check if verification code matches
        if ($user->verification_code !== $request->verification_code) {
            throw new GeneralException(trans('default.invalid_verification_code'));
        }

        // Optionally: mark user as verified if you track it
        $user->email_verified_at = now();
        $user->verification_code = null; // Clear the code after successful verification
        $user->save();

        return redirect()
            ->route('users.verify.mail') // adjust this route if your login page is different
            ->with('success', trans('default.email_verified_successfully'));
    }
}
