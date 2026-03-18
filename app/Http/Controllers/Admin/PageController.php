<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    public function index()
{
    $pages = Page::paginate(10); // You can adjust the number per page as needed
    return view('admin.pages.index', compact('pages'));
}


    public function show(Page $page)
    {
        return view('front.pages.details', compact('page'));
    }


    public function pageProxy($slug = null) {
        if (!$slug) {
            // If no slug is provided, redirect to the home page or any other default page
            return redirect(route('home')); // Adjust 'home' route as per your routes
        }

        $page = Page::where('slug', $slug)->first();

        if (!$page) {
            abort(404);
        }

        return redirect(route('page', $slug));
    }



    // Admin methods

    public function create()
    {
        return view('admin.pages.create');
    }



    public function edit($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return view('admin.pages.edit', compact('page'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'featured_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('featured_image');

        // Set the slug if provided, otherwise generate it from the title
        $data['slug'] = $request->input('slug') ?? Str::slug($request->input('title'));

        // Generate a unique ID
        $id = Str::uuid()->toString(); // Convert UUID object to string

        // Create a new Page instance and set its properties
        $page = new Page();
        $page->id = $id;
        $page->title = $data['title'];
        $page->content = $data['content'];
        $page->slug = $data['slug'];
        $page->user_id = auth()->id(); // Assuming you have authentication set up
        // Handle featured image if provided
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('featured_images', 'public');
            $page->featured_image = $imagePath;
        }

        // Save the page to the database
        $page->save();

        return redirect()->route('admin.pages.index');
    }


    public function update(Request $request, $slug)
{
    $page = Page::where('slug', $slug)->firstOrFail();

    $request->validate([
        'title' => 'required',
        'content' => 'required',
        'featured_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $data = $request->except(['featured_image', 'remove_featured_image']);

    // Check if the user wants to remove the existing image
    if ($request->has('remove_featured_image')) {
        // Delete old image if it exists
        if ($page->featured_image) {
            Storage::disk('public')->delete($page->featured_image);
            $data['featured_image'] = null; // Update database to remove the image path
        }
    } elseif ($request->hasFile('featured_image')) {
        // Delete old image if it exists
        if ($page->featured_image) {
            Storage::disk('public')->delete($page->featured_image);
        }

        $imagePath = $request->file('featured_image')->store('featured_images', 'public');
        $data['featured_image'] = $imagePath;
    }

    // Set the slug if provided, otherwise generate it from the title
    $data['slug'] = $request->input('slug') ?? Str::slug($request->input('title'));

    // Ensure unique slug by appending a unique value
    $data['slug'] = $this->makeSlugUnique($data['slug'], $page->id);

    $page->update($data);

    return redirect()->route('admin.pages.index');
}


    // Helper function to make slug unique
    private function makeSlugUnique($slug, $id = null)
    {
        $count = Page::where('slug', $slug)->where('id', '<>', $id)->count();

        return $count ? $slug . '-' . ($count + 1) : $slug;
    }



    public function destroy($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        // Delete the featured image if it exists
        if ($page->featured_image) {
            Storage::disk('public')->delete($page->featured_image);
        }

        $page->delete();
        return redirect()->route('admin.pages.index');
    }


public function deleteImage($slug)
{
    $page = Page::where('slug', $slug)->firstOrFail();

    if ($page->featured_image) {
        // Delete the image from storage
        Storage::disk('public')->delete($page->featured_image);
        // Remove the image path from the database
        $page->featured_image = null;
        $page->save();

        return response()->json(['message' => 'Image deleted successfully'], 200);
    }

    return response()->json(['message' => 'No image found to delete'], 404);
}
}
