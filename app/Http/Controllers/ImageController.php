
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        // Handle image upload here
        // You can use Laravel's Filesystem, Storage, or any other method

        // Example: Store the uploaded file in the 'public' disk
        $path = $request->file('upload')->store('public/uploads');

        // Return the URL of the uploaded image
        $url = asset(str_replace('public', 'storage', $path));

        return response()->json(['url' => $url]);
    }
}
