<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\LostPasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ValidateRegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\SendEmailPasswordLost;
use App\Notifications\SendEmailPasswordResetOK;
use App\Notifications\SendEmailValidateUser;
use App\Notifications\SendEmailValidationUserOk;
use App\Repositories\PasswordResetRepository;
use App\Repositories\UserHistoEmailRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    private $userRepository;

    private $userHistoEmailRepository;

    private $passwordResetRepository;

    public function __construct(
        UserRepository $userRepository,
        UserHistoEmailRepository $userHistoEmailRepository,
        PasswordResetRepository $passwordResetRepository
    ) {
        $this->userRepository = $userRepository;
        $this->userHistoEmailRepository = $userHistoEmailRepository;
        $this->passwordResetRepository = $passwordResetRepository;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->userRepository->insert($request->all());

            $user->notify(new SendEmailValidateUser());

            return new UserResource($user);
        } catch (\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage()
            ], 405);
        }
    }

    public function validateRegistration(ValidateRegistrationRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        try {
            $this->userRepository->validateUser($user);
            $user->notify(new SendEmailValidationUserOk());

            return response()->json(['message' => 'User validated.']);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 422);
        }
    }

    public function login(LoginRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'user_state_id' => 'VALIDATED'
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        try {
            $user = User::where('email', $request->email)->first();
            $user->tokens()->delete();
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return response()->json(['token' => $tokenResult, 'user' => $user->only(['id', 'role_id', 'first_name', 'last_name', 'organization_id'])]);
            /*
            $auth = [
                'auth' => [
                    'token' => $tokenResult,
                    'user' => $user->only(['id', 'role_id', 'first_name', 'last_name'])
                ]
            ];

            return response()->json($auth);
            */
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json(['message' => 'User deconnected'], 200);
    }

    public function lostPassword(LostPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        $messageReturn = 'Si un utilisateur avec cet email existe, alors un email vient de lui être envoyé.';

        if (!$user) {
            Log::info('[PASSWORD_LOST] No user found for the email ' . $request->email);

            return response()->json(['message' => $messageReturn], 200);
        }

        if ($user) {
            $quantityEmailSent = $this->userHistoEmailRepository->userHistoEmailOfTheDay($user->id, 'PASSWORD_LOST');
        }

        $token = Str::random();
        if ($quantityEmailSent < config('params.max_emails_forgot_password_a_day')) {
            try {
                $this->passwordResetRepository->insert($user->email, $token);
                $user->notify(new SendEmailPasswordLost($token, config('params.delay_validity_token_reset_password')));
                $this->userHistoEmailRepository->insert($user->id, 'PASSWORD_LOST');
            } catch (\Exception $ex) {
                Log::error($ex->getMessage());
            }
        } else {
            Log::info('[PASSWORD_LOST] Max email sent for PASSWORD_LOST for today for email ' . $request->email);
        }

        return response()->json(['message' => $messageReturn], 200);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        DB::beginTransaction();
        try {
            $this->userRepository->changePassword($user, $request->password);
            $this->passwordResetRepository->token_used($request->token);

            $user->notify(new SendEmailPasswordResetOK());
            DB::commit();

            return response()->json(['message' => 'mot de passe modifié.'], 200);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            DB::rollBack();

            return response()->json(['message' => 'Erreur à le modification du mot de passe.'], 500);
        }
    }
}
