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
         return 'No authenticated user with a valid email address.';
      }

      Mail::to($user->email)->send(new WelcomeMail($user->name ?? 'there'));
        return "Email sent successfully";
   }
}
