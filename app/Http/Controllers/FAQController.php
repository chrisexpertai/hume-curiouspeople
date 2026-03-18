<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Course; // Add this line to import the Course model
use Illuminate\Http\Request;

class FaqController extends Controller
{
    // Store a new FAQ
    public function store(Request $request, $id)
    {
        // Validation rules
        $validatedData = $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        // Find the course by ID
        $course = Course::findOrFail($id);

        // Create and store the FAQ associated with the course
        $faq = new Faq();
        $faq->question = $validatedData['question'];
        $faq->answer = $validatedData['answer'];
        $faq->course_id = $course->id;
        $faq->user_id = auth()->id(); // Assuming user is authenticated
        $faq->save();

        // Return success response
        return response()->json(['message' => 'FAQ created successfully'], 200);
    }


    // Edit an existing FAQ
    public function edit($id)
    {
        // Find the FAQ by ID
        $faq = Faq::findOrFail($id);

        // Pass the FAQ to the view for editing
        return view('faqs.edit', compact('faq'));
    }

    // Update an existing FAQ
    public function update(Request $request, $faq_id) // Change parameter name to $faq_id
    {
        // Validation rules
        $validatedData = $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        // Find the FAQ by ID
        $faq = Faq::findOrFail($faq_id); // Update parameter name

        // Update the FAQ with the validated data
        $faq->question = $validatedData['question'];
        $faq->answer = $validatedData['answer'];
        $faq->save();

        // Redirect back to the additional page with a success message
        return redirect()->route('edit_course_additional', $faq->course_id)->with('success', 'FAQ updated successfully');
    }

    // Delete an existing FAQ
    public function destroy($id, $faq_id)
    {
        // Find the FAQ by ID and delete it
        $faq = Faq::findOrFail($faq_id);
        $course_id = $faq->course_id;
        $faq->delete();

        // Redirect back to the additional page with a success message
        return redirect()->route('edit_course_additional', $course_id)->with('success', 'FAQ deleted successfully');
    }

}
