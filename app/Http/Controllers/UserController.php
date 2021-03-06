<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ValidateRegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Rules\CheckEmailVerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->userRepository->insert($request->all());

            // @todo : send email to validate the user.
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
        // dd($user);
        try {
            $this->userRepository->changeUserStatus($user, 'VALIDE');

            return response()->json(['message' => 'User validated.']);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 422);
        }
    }

    public function login(LoginRequest $request)
    {
        // $credentials = request(['email', 'password']);
        $credentials = Arr::add(request(['email', 'password']), 'status', 'ACTIVE');
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        try {
            $user = User::where('email', $request->email)->first();
            $user->tokens()->delete();
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return response()->json(['token' => $tokenResult]);
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
}
