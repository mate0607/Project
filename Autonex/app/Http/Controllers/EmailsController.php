<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class EmailsController extends Controller
{
    public function WelcomeEmail()
    {
        $user = Auth::user();

        if (!$user || empty($user->email)) {
            return response()->json(['error' => 'No authenticated user with a valid email address.'], 422);
        }

        Mail::to($user->email)->send(new WelcomeMail($user->name ?? 'there'));

        return response()->json(['message' => 'Email sent successfully.']);
    }
}
