<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

class ContactFormController extends Controller
{
    public function sendMail(Request $request)
        {
        
            $data = [    
                'name' => $request->nom,
                'email' => $request->email,
                'content' => $request->avis
            ];

            Mail::to('contact@on-en-parle.ch')->send(new ContactMail($data));

            return redirect()->back()->with('success', 'Courriel envoyé avec succès !');
        }
}
