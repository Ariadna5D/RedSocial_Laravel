<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // Obtenemos los posts con su autor y el conteo de likes
        $posts = Post::with('user')->withCount('likes')->latest()->get();

        return view('social.home', compact('posts'));
    }

    public function like(Post $post)
    {

        return back()->with('success', '¡Acción de like procesada!');
    }

    public function edit(Post $post)
    {

    }

    
    public function delete(Post $post)
    {
        $post->delete();

        return redirect()->route('index')->with('success', 'Post Eliminado');
    }

    public function show(Post $post) 
    {
        $post->load('user'); 
        
        return view('social.post-detail', compact('post'));
    }
}
