<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function showForm()
    {
        // You can add logic here to retrieve necessary data for the view
        $contactSettings = [
            'background' => 'your_background_image_url',
            'latitude' => 'your_latitude',
            'longitude' => 'your_longitude',
            'map_zoom' => 'your_map_zoom',
            // Add other contact settings as needed
        ];

        return view(('front.contact'), compact('contactSettings'));
    }

    public function contacts(Request $request)
    {
        // Get the sorting option from the query string
        $sortOption = $request->query('sort', 'latest'); // Default to 'latest' if not provided

        // Implement sorting logic based on the selected option
        if ($sortOption == 'oldest') {
            $contacts = Contact::oldest()->get();
        } else {
            $contacts = Contact::latest()->get();
        }

        return view('admin.contacts.index', compact('contacts'));
    }

    public function destroy($id)
    {
        try {
            $contact = Contact::findOrFail($id);
            $contact->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function store(Request $request)
    {
        // Add your form validation logic here
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            //  'captcha' => 'required|captcha', // Assuming you are using Laravel's built-in captcha validation
        ]);

        // Store the contact form data in the database
        $contact = Contact::create($validatedData);

        // Flash a success message to the session
        return redirect('/contact')->with('msg', 'Your message has been sent successfully!');
    }
}
