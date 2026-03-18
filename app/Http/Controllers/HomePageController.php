<?php

namespace App\Http\Controllers;
 
use App\Models\Post;
use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\HomePage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class HomePageController extends Controller
{
    public function index()
    {
        $homePages = HomePage::all();
        return view('front.index', compact('homePages'));
    }


    public function show($slug)
    {
        $homePage = HomePage::where('slug', $slug)->firstOrFail();
        return view('homepages.show', compact('homePage'));
    }
    public function create()
    {
        return view('admin.homepages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:home_pages',
            'is_active' => 'required',
            // Add validation for other fields
        ]);
    
        $grapesjsContent = json_decode($request->input('grapesjs_content'), true);
        $cleanHtmlContent = $this->convertGrapesJsToCleanHtml($grapesjsContent);
    
        HomePage::create([
            'title' => $request->input('title'),
            'slug' => $request->input('slug'),
            'content' => $cleanHtmlContent,
            'is_active' => $request->input('is_active'),
        ]);
    
        return redirect()->route('homepages.index');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'slug' => 'required|unique:home_pages,slug,' . $id,
            'is_active' => 'required',
            // Add validation for other fields
        ]);
    
        $grapesjsContent = json_decode($request->input('grapesjs_content'), true);
        $cleanHtmlContent = $this->convertGrapesJsToCleanHtml($grapesjsContent);
    
        $homePage = HomePage::findOrFail($id);
        $homePage->update([
            'title' => $request->input('title'),
            'slug' => $request->input('slug'),
            'content' => $cleanHtmlContent,
            'is_active' => $request->input('is_active'),
        ]);
    
        return redirect()->route('homepages.index');
    }
    


    public function edit($id)
    {
        $homePage = HomePage::findOrFail($id);
        return view('admin.homepages.edit', compact('homePage'));
    }

  
    public function destroy($id)
    {
        $homePage = HomePage::findOrFail($id);
        $homePage->delete();

        return redirect()->route('homepages.index');
    }
      // Helper function to convert GrapesJS content to clean HTML
      private function convertGrapesJsToCleanHtml($grapesjsContent)
{
    // Your logic to process $grapesjsContent and convert it to clean HTML
    // For simplicity, let's assume you use the cleanHtml function from GrapesJS
    $cleanHtml = cleanHtml($grapesjsContent);

    return $cleanHtml;
}

}