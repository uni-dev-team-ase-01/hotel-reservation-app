<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10',
        ]);
        try {
            Contact::create($validated);

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
