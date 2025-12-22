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
                return redirect()->back()->with('success', 'Welcome back! You have successfully resubscribed to our newsletter.');
            }
            return redirect()->back()->with('info', 'You are already subscribed to our newsletter.');
        }

        NewsletterSubscriber::create([
            'email' => $request->email,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Thank you for subscribing to our newsletter!');
    }
}
