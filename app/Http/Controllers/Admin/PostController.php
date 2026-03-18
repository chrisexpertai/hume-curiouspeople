<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tag;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller


{

    public function index()
    {
        $posts = Post::all();
        $posts = Post::paginate(8);

        return view('front.posts.index', compact('posts'));
    }

    public function show(Post $post)

    {
        $posts = Post::all();

        $post->increment('views');

        return view('front.posts.details', compact('post','posts'));
    }

    public function posts_admin()
    {
         $posts = Post::paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function edit($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        return view('admin.posts.edit', compact('post'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'short_description' => 'required',
            'content' => 'required',
            'featured_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $post = new Post();
        $post->title = $request->input('title');
        $post->short_description = $request->input('short_description');
        $post->content = $request->input('content');
        $post->slug = $request->input('slug') ?? Str::slug($request->input('title'));
        $post->user_id = auth()->id();
        $post->topic = $request->input('topic'); // New line to handle topic


        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('featured_images', 'public');
            $post->featured_image = $imagePath;
        }

        $post->save();

        // Attach tags to the post
        $tags = explode(',', $request->input('tags'));
        $tagIds = [];
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => trim($tagName)]);
            $tagIds[] = $tag->id;
        }
        $post->tags()->sync($tagIds);

        return redirect()->route('admin.posts.index');
    }

    public function update(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'featured_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('featured_image', 'tags'); // Exclude tags from update

        if ($request->hasFile('featured_image')) {
            // Delete old image if it exists
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }

            $imagePath = $request->file('featured_image')->store('featured_images', 'public');
            $data['featured_image'] = $imagePath;
        }

        // Set the slug if provided, otherwise generate it from the title
        $data['slug'] = $request->input('slug') ?? Str::slug($request->input('title'));

        // Ensure unique slug by appending a unique value
        $data['slug'] = $this->makeSlugUnique($data['slug'], $post->id);

        // Update the post with the new data
        $post->update($data);

        // Attach tags to the post
        $tags = explode(',', $request->input('tags'));
        $tagIds = [];
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => trim($tagName)]);
            $tagIds[] = $tag->id;
        }

        // Detach existing tags and then attach the new ones
        $post->tags()->detach();
        $post->tags()->attach($tagIds);

        return redirect()->route('admin.posts.index');
    }



    // Helper function to make slug unique
    private function makeSlugUnique($slug, $id = null)
    {
        $count = Post::where('slug', $slug)->where('id', '<>', $id)->count();

        return $count ? $slug . '-' . ($count + 1) : $slug;
    }



public function deleteImage($slug)
{
    $post = Post::where('slug', $slug)->firstOrFail();

    if ($post->featured_image) {
        // Delete the image from storage
        Storage::disk('public')->delete($post->featured_image);
        // Remove the image path from the database
        $post->featured_image = null;
        $post->save();

        return response()->json(['message' => 'Image deleted successfully'], 200);
    }

    return response()->json(['message' => 'No image found to delete'], 404);
}


    public function destroy($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        // Delete the featured image if it exists
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();
        return redirect()->route('admin.posts.index');
    }
}
