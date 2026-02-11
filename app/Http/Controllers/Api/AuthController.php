<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->error('Invalid credentials', 401);
            }

            // Update last_login_at
            $user = JWTAuth::user();
            $user->update(['last_login_at' => now()]);

            return $this->success([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
                'user' => JWTAuth::user(),
            ], 'Login successful');

        } catch (JWTException $e) {
            return $this->error('Could not create token', 500);
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }

    public function logout()
    {
        try {
            $token = JWTAuth::getToken();

            if (!$token) {
                return $this->error('Token not provided', 401);
            }

            JWTAuth::invalidate($token);

            return $this->success(null, 'Logged out successfully');

        } catch (TokenExpiredException $e) {
            return $this->error('Token already expired', 401);
        } catch (TokenInvalidException $e) {
            return $this->error('Token is invalid', 401);
        } catch (JWTException $e) {
            return $this->error('Could not log out', 500);
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }

    public function me()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return $this->error('User not found', 404);
            }

            return $this->success($user, 'User profile retrieved successfully');

        } catch (TokenExpiredException $e) {
            return $this->error('Token has expired', 401);
        } catch (TokenInvalidException $e) {
            return $this->error('Token is invalid', 401);
        } catch (JWTException $e) {
            return $this->error('Token not provided', 401);
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }
}
