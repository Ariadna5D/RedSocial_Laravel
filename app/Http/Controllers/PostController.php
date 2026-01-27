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
        return view('social.post-edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:600',
        ]);

        $post->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'edited_by' => auth()->user()->getRoleNames()->first() ?? 'Usuario',
        ]);

        return redirect()->route('posts.show', $post->id)
            ->with('success', 'Post actualizado correctamente.');
    }

    public function delete(Post $post)
    {
        $post->delete();

        return redirect()->route('index')->with('success', 'Post Eliminado');
    }

    public function show(Post $post)
    {
        $post->load([
            'user',
            'comments' => function ($query) {
                $query->with('user')->withCount('likes'); // Carga autor y cuenta likes del comentario
            },
        ]);

        return view('social.post-detail', compact('post'));
    }
}
