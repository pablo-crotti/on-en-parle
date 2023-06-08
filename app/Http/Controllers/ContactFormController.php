<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactFormController extends Controller
{
    public function sendEmail(Request $request)
        {
            console.log($request);
            $data = [
                'subject' => $request->input('subject'),
                'content' => $request->input('content')
            ];

            Mail::to('')->send(new ContactMail($data));

            // Autres actions ou redirections après l'envoi du courriel

            // Par exemple :
            return redirect()->back()->with('success', 'Courriel envoyé avec succès !');
        }
}
