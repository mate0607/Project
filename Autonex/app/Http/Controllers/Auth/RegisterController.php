<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function registered(Request $request, $user)
    {
        Log::info('WELCOME_MAIL: registered() hook reached.', [
            'user_id' => $user->id,
            'email'   => $user->email,
            'already_sent' => !is_null($user->welcome_email_sent_at),
        ]);

        if (!empty($user->email) && is_null($user->welcome_email_sent_at)) {
            try {
                Mail::to($user->email)->send(new WelcomeMail($user->name ?? 'there'));

                $user->update(['welcome_email_sent_at' => now()]);

                Log::info('WELCOME_MAIL: sent successfully.', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                ]);
            } catch (\Throwable $e) {
                Log::error('WELCOME_MAIL: send FAILED.', [
                    'user_id'   => $user->id,
                    'email'     => $user->email,
                    'exception' => get_class($e),
                    'message'   => $e->getMessage(),
                ]);
            }
        }

        return redirect($this->redirectPath());
    }
}
