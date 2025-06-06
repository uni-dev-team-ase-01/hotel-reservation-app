<?php

namespace App\Http\Controllers;

use App\Models\TravelCompany;
use Illuminate\Http\Request;

class TravelCompanyController extends Controller
{
    public function index()
    {
        return view('travel-agent.index');
    }
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'company_name' => 'required|string|max:255',
                'email' => 'required|email|unique:travel_companies,email',
                'phone' => 'required|string|max:20',
                'message' => 'required|string|max:1000',
            ]);

            TravelCompany::create($validated);

            return back()->with('success', 'Thank you for contacting us. We’ll get back to you soon!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Travel company store error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Oops! Something went wrong. Please try again later.')
                ->withInput();
        }
    }


}
