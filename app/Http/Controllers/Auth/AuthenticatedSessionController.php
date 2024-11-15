<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthenticatedSessionController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();

            $data['password'] = Hash::make($data['password']);

            DB::beginTransaction();
            $user = User::create($data);

            $token = $user->createToken("register-token for {$user->email}");

            //event(new Registered($user));

            DB::commit();

            Auth::login($user);

            return response()->json([
                'message' => 'Вы успешно зарегистрировались',
                'token' => $token->plainTextToken
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json([
                'message' => 'Произошла ошибка при регистрации, повторите попытку'
            ], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $data = $request->validated();

            $user = User::query()
                ->where('email', $data['email'])
                ->first();

            if (!$user || !Hash::check($data['password'], $user->password)) {
                return response()->json([
                    'message' => 'Неверно введены данные'
                ], 401);
            }

            $token = $user->createToken("login-token for {$user->email}");

            return response()->json([
                'message' => 'Вы успешно авторизовались',
                'token' => $token->plainTextToken
            ],200);
        } catch (\Exception $e) {
            Log::error('Ошибка авторизации', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Ошибка авторизации, повторите попытку'
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Вы вышли из системы',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Ошибка выхода из системы', [
                'error' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            return response()->json([
                'message'=> 'Не удалось выйти с системы, повторите попытку'
            ], 500);
        }
    }
}
