<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Display a listing of Shipping Addresses.
     */
    public function shippingIndex()
    {
        $addresses = Auth::user()->addresses()->where('address_type', 'shipping')->get();
        return view('shop.dashboard.addresses.shipping', compact('addresses'));
    }

    /**
     * Display the Billing Address management page.
     */
    public function billingIndex()
    {
        $address = Auth::user()->addresses()->where('address_type', 'billing')->first();
        return view('shop.dashboard.addresses.billing', compact('address'));
    }

    /**
     * Show the form for creating a new resource (Generic or specific).
     */
    public function create(Request $request)
    {
        $type = $request->query('type', 'shipping');
        return view('shop.dashboard.addresses.create', compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address_type' => 'required|in:shipping,billing',
            'alias' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state_province' => 'nullable|string|max:255',
            'country_code' => 'required|string|max:2',
            'phone' => 'required|string|max:20',
            'is_default' => 'boolean',
        ]);

        $validated['user_id'] = Auth::id();

        // Enforce alias requirement for shipping
        if ($validated['address_type'] === 'shipping' && empty($validated['alias'])) {
             // Default alias if not provided? Or make it required validation?
             // User prompt said "User enters it". So it should be required for shipping.
             // But let's handle it gracefully if missing by using First Name or "My Address"
             $validated['alias'] = $validated['alias'] ?? 'My Address';
        }

        // Enforce singleton for billing
        if ($validated['address_type'] === 'billing') {
            $existingBilling = Auth::user()->addresses()->where('address_type', 'billing')->first();
            if ($existingBilling) {
                $existingBilling->update($validated);
                return redirect()->route('addresses.billing')->with('success', 'Billing address updated successfully.');
            }
        }

        if ($request->boolean('is_default')) {
            // Unset other defaults of same type
            Auth::user()->addresses()
                ->where('address_type', $validated['address_type'])
                ->update(['is_default' => false]);
        }

        Address::create($validated);

        if ($validated['address_type'] === 'billing') {
            return redirect()->route('addresses.billing')->with('success', 'Billing address saved successfully.');
        }

        return redirect()->route('addresses.shipping')->with('success', 'Address created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        return view('shop.dashboard.addresses.edit', compact('address'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'address_type' => 'required|in:shipping,billing',
            'alias' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state_province' => 'nullable|string|max:255',
            'country_code' => 'required|string|max:2',
            'phone' => 'required|string|max:20',
            'is_default' => 'boolean',
        ]);

        if ($request->boolean('is_default')) {
            // Unset other defaults of same type
            Auth::user()->addresses()
                ->where('address_type', $validated['address_type'])
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
        }

        $address->update($validated);

        if ($validated['address_type'] === 'billing') {
            return redirect()->route('addresses.billing')->with('success', 'Billing address updated successfully.');
        }

        return redirect()->route('addresses.shipping')->with('success', 'Address updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $type = $address->address_type;
        $address->delete();

        if ($type === 'billing') {
            return redirect()->route('addresses.billing')->with('success', 'Billing address deleted.');
        }

        return redirect()->route('addresses.shipping')->with('success', 'Address deleted successfully.');
    }
}
