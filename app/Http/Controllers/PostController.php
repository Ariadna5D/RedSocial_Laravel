<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user'])
            ->withCount('likes')
            ->with(['likes' => function ($query) {
                $query->where('user_id', auth()->id());
            }])
            ->latest()
            ->get();

        return view('social.home', compact('posts'));
    }

    public function delete(Post $post)
    {
        if (auth()->id() !== $post->user_id && ! auth()->user()->can('delete post')) {
            abort(403, 'No tienes permiso para borrar este post.');
        }

        $post->delete();

        return redirect()->route('index')->with('success', 'Post Eliminado');
    }

    public function show(Post $post)
    {
        // Cargamos todo de golpe para evitar que una línea pise a la anterior
        $post->load([
            'user',
            'likes',
            'comments.user',
            'comments.likes', // Cargamos los likes para saber si el usuario actual dio like
        ])->loadCount([
            'likes',
        ]);

        // Importante: Para que el contador de likes de cada COMENTARIO aparezca,
        // necesitamos cargar ese conteo específico en la relación de comentarios.
        $post->comments->each(function ($comment) {
            $comment->loadCount('likes');
        });

        return view('social.post-detail', compact('post'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:600',
        ]);

        $request->user()->posts()->create($validated);

        return back()->with('success', '¡Post publicado con éxito!');
    }

    public function like(Post $post)
    {
        $user = auth()->user();

        // Buscamos si ya existe el like usando la relación polimórfica
        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            // Si ya existe, lo quitamos (Toggle Off)
            $like->delete();
            $mensaje = 'Like retirado';
        } else {
            // Si no existe, lo creamos (Toggle On)
            $post->likes()->create([
                'user_id' => $user->id,
            ]);
            $mensaje = '¡Te gusta este post!';
        }

        return back()->with('success', $mensaje);
    }

    // PostController.php

    public function edit(Post $post)
    {
        // Si NO es el dueño Y NO tiene permiso de editar posts ajenos...
        if (auth()->id() !== $post->user_id && ! auth()->user()->can('edit post')) {
            abort(403, 'No tienes permiso para editar este post.');
        }

        return view('social.post-edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        // Repetimos la validación por seguridad
        if (auth()->id() !== $post->user_id && ! auth()->user()->can('edit post')) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:600',
        ]);

        $post->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'edited_by' => auth()->user()->getRoleNames()->first() ?? 'Usuario',
        ]);

        return redirect()->route('posts.show', $post->id)->with('success', 'Post actualizado.');
    }
}
