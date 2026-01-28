<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
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
        $post->load([
            'user',
            'likes',
            'comments' => function ($query) {
                $query->latest();
            },
            'comments.user',
            'comments.likes',
        ])->loadCount(['likes']);

        $post->comments->each(function ($comment) {
            $comment->loadCount('likes');
        });

        return view('social.post-detail', compact('post'));
    }

    public function store(StorePostRequest $request)
    {

        $request->user()->posts()->create($request->validated());

        return back()->with('success', '¡Post publicado!');
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

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update(array_merge($request->validated(), [
            'edited_by' => auth()->user()->getRoleNames()->first() ?? 'Usuario',
        ]));

        return redirect()->route('posts.show', $post->id)->with('success', 'Post actualizado.');
    }
}
