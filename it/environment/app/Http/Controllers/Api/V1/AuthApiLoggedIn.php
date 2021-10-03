<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;

// Auto Configured by (IT) Baboon maker (phpanonymous/it package)

class AuthApiLoggedIn extends Controller {

	/**
	 * Create a new AuthController instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('auth:api', ['except' => ['login']]);
	}

	private function auth() {
		return auth()->guard('api');
	}
	/**
	 * Get the token array structure.
	 *
	 * @param  string $token
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function respondWithToken($token) {
		return [
			'access_token' => $token,
			'token_type' => 'Bearer',
			'expires_in' => $this->auth()->factory()->getTTL() * 60,
			'user' => $this->auth()->user(),
		];
	}

	/**
	 * Get the authenticated User.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function me() {
		return successResponseJson(['data' => $this->auth()->user()]);
	}

	/**
	 * Log the user out (Invalidate the token).
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function logout() {
		$this->auth()->logout();
		return successResponseJson(['message' => 'Successfully logged out']);
	}

	/**
	 * Refresh a token.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function refresh() {
		return successResponseJson(['data' => $this->respondWithToken($this->auth()->refresh())]);
	}

	public function account() {
		return successResponseJson(['data' => $this->auth()->user()]);
	}

	/**
	 * Get a JWT via given credentials.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function login() {
		$this->validate(request(), [
			'email' => 'required|email',
			'password' => 'required',
		], [], [
			'email' => trans('admin.email'),
			'password' => trans('admin.password'),
		]);
		$credentials = request(['email', 'password']);

		try {
			if (!$token = $this->auth()->attempt($credentials)) {
				return errorResponseJson(['error' => 'Unauthorized', 'message' => trans('auth.failed')]);
			}
		} catch (JWTException $e) {
			return errorResponseJson(['error' => 'Unauthorized', 'message' => 'Could not create token']);
		}

		return successResponseJson(['data' => $this->respondWithToken($token)]);
	}

}