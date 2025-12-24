<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        // Check if already subscribed
        $subscriber = NewsletterSubscriber::where('email', $request->email)->first();

        if ($subscriber) {
            if (!$subscriber->is_active) {
                // Reactivate if previously unsubscribed
                $subscriber->update(['is_active' => true]);
                return redirect()->back()->with('success', '¡Bienvenido de nuevo! Te has vuelto a suscribir con éxito a nuestro boletín.');
            }
            return redirect()->back()->with('info', 'Ya estás suscrito a nuestro boletín.');
        }

        NewsletterSubscriber::create([
            'email' => $request->email,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Gracias por suscribirte a nuestro boletín!');
    }
}
