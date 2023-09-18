<?php

namespace Vesaka\Games\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\{PasswordReset, Registered, Authenticated, Verified};
use Illuminate\Http\{Request, RedirectResponse};
use Illuminate\Support\Facades\{Hash, Password};
use Illuminate\Support\Str;
use Vesaka\Games\Http\Requests\Auth\{
    LoginRequest,
    RegisterRequest,
    ResetPasswordRequest,
    ForgotPasswordRequest,
    CreateGuestRequest
};
use Vesaka\Games\Models\Player;
use Illuminate\Validation\ValidationException;
use Vesaka\Games\Http\Controllers\GameController;

/**
 * Description of AuthController
 *
 * @author vesak
 */
class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $request->authenticate();
        try {
            $user = $request->user()->only('id', 'name');
            $user['token'] = $request->user()->createToken($request->header('User-Agent'))->plainTextToken;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
        event(new Authenticated('sanctum', $request->user()));
        return $user;
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Vesaka\Games\Http\Requests\Auth\RegisterRequest  $request
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(RegisterRequest $request)
    {
        $user = Player::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        event(new Registered($user));

        return $user->only('id', 'name');
    }

    public function createGuest(CreateGuestRequest $request)
    {
        $name = Str::random(10);
        $player = Player::create([
            'name' => $name,
            'email' => $name . GUEST_EMAIL_DOMAIN,
            'password' => Hash::make(Str::random(10)),
        ]);
        $player->markEmailAsVerified();
        $player->save();

        $user = $player->only('id', 'name');
        $user['token'] = $player->createToken($request->header('User-Agent'))->plainTextToken;

        return $user;
    }

    public function sendPasswordResetLink(ForgotPasswordRequest $request) {

        $status = Password::sendResetLink(
            $request->only('email')
        );

        Password::sendResetLink(['email' => $request->only('email')], function ($user, $token) {
            $player = Player::find($user->id);
            $player->sendPasswordResetNotification($token);
        });

        if ($status == Password::RESET_LINK_SENT) {
            return ['message' => 'Sent'];
        }

        throw ValidationException::withMessages([
            'email' => $status,
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return ['status' => $status];
        }

        throw ValidationException::withMessages([
            'password' => 'token',
        ]);
    }

    public function verifyEmail(Request $request)
    {
        $player = Player::find($request->id);
        if (!$player) {
            return $this->getRedirectUrl($request, ['path' => '404']);
        }

        if ($player->hasVerifiedEmail()) {
            return $this->getRedirectUrl($request);
        }

        if ($player->markEmailAsVerified()) {
            event(new Verified($player));
        }

        return $this->getRedirectUrl($request);
    }

    public function resetPasswordForm(Request $request)
    {
        return action([GameController::class, 'spa'], [$request]);
    }

    protected function getRedirectUrl(Request $request, array $query = []): RedirectResponse
    {
        return redirect()->route('game::spa', array_merge(
            ['game' => $request->game, 'verified' => '1'],
            $query
        ));
    }
}
