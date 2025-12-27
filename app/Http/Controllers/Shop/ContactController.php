<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Mail\ContactForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $recipient = env('SHOP_EMAIL');

        if ($recipient) {
            Mail::to($recipient)->send(new ContactForm($request->all()));
        }

        return back()->with('success', 'Su mensaje ha sido enviado correctamente. Nos pondremos en contacto pronto.');
    }
}
