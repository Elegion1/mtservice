<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::all();
        return view('dashboard.customer', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        $validated = $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'dial_code' => 'nullable',
            'discount' => 'nullable',
            'body' => 'nullable',
        ]);

        $customer = new Customer();
        $customer->name = $validated['name'];
        $customer->surname = $validated['surname'];
        $customer->email = $validated['email'];
        $customer->dial_code = $validated['dial_code'];
        $customer->phone = $validated['phone'];
        $customer->discount = $validated['discount'];
        $customer->body = $validated['body'];
        $customer->save();

        return redirect()->route('dashboard.customer')->with('success', 'Cliente creato con successo');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'discount' => 'nullable',
            'body' => 'nullable',
        ]);

        $customer->update($validated);

        return redirect()->route('dashboard.customer')->with('success', 'Dati Cliente aggiornati con successo!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('dashboard.customer')->with('success', 'Cliente eliminato con successo.');
    }
}
