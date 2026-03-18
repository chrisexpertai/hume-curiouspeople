<?
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $filename, 'public');

            // Return JSON response for CKEditor
            return response()->json([
                'url' => asset('public/uploads/images/' . $filePath)
            ]);
        }
        return response()->json(['error' => 'No file uploaded.'], 400);
    }
}
