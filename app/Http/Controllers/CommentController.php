<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommentController extends Controller
{
    /**
     * Procesa el Like en un comentario usando relación polimórfica.
     */
    public function like(Comment $comment): RedirectResponse
    {
        // El método likes() viene de tu MorphMany en el modelo
        $like = $comment->likes()->where('user_id', auth()->id())->first();

        if ($like) {
            $like->delete();
            $mensaje = 'Like eliminado.';
        } else {
            $comment->likes()->create(['user_id' => auth()->id()]);
            $mensaje = '¡Like añadido!';
        }

        return back()->with('success', $mensaje);
    }

    /**
     * Muestra el formulario de edición o procesa la actualización.
     */
    public function edit(Request $request, Comment $comment)
    {
        // Laravel 11: Gate::authorize lanza automáticamente el 403 si falla
        // Permite editar si es el dueño O tiene el permiso
        Gate::authorize('update-comment', $comment);

        if ($request->isMethod('get')) {
            return view('social.comment-edit', compact('comment'));
        }

        $validated = $request->validate([
            'reply' => 'required|string|max:200',
        ]);

        $comment->update([
            'reply' => $validated['reply'],
            'edited_by' => auth()->user()->name // Usamos el campo de tu migración
        ]);

        return redirect()->route('posts.show', $comment->post_id)
                         ->with('success', 'Comentario actualizado.');
    }

    /**
     * Elimina el comentario de forma segura.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        // Validamos seguridad con Gate
        Gate::authorize('delete-comment', $comment);

        $postId = $comment->post_id;
        $comment->delete();

        return redirect()->route('posts.show', $postId)
                         ->with('success', 'Comentario eliminado.');
    }
}