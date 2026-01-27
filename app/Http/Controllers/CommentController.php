<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Procesa el Like en un comentario usando relación polimórfica.
     */
    // CommentController.php - Método like corregido
    public function like(Request $request, Comment $comment): RedirectResponse
    {
        // SEGURIDAD LÓGICA: Evitamos que un usuario se dé like a sí mismo
        if (auth()->id() === $comment->user_id) {
            return back()->with('error', 'No puedes darte like a tu propio comentario.');
        }

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
    // CommentController.php

public function edit(Comment $comment)
{
    if (auth()->id() !== $comment->user_id && !auth()->user()->can('edit comment')) {
        abort(403);
    }
    return view('social.comment-edit', compact('comment'));
}

public function update(Request $request, Comment $comment)
{
    if (auth()->id() !== $comment->user_id && !auth()->user()->can('edit comment')) {
        abort(403);
    }

    $validated = $request->validate([
        'reply' => 'required|string|max:200',
    ]);

    $comment->update([
        'reply' => $validated['reply'],
        'edited_by' => auth()->user()->getRoleNames()->first() ?? 'Usuario',
    ]);

    return redirect()->route('posts.show', $comment->post_id)
        ->with('success', 'Comentario actualizado.');
}

    /**
     * Guarda un nuevo comentario.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'reply' => 'required|string|max:200',
        ]);

        // Usamos la relación definida en el modelo User para asignar el user_id automáticamente
        $request->user()->comments()->create($validated);

        return back()->with('success', 'Comentario añadido.');
    }

    /**
     * Elimina el comentario de forma segura.
     */
   public function destroy(Comment $comment): RedirectResponse
    {
        // SEGURIDAD MANUAL:
        // Solo puede borrarlo si es el dueño O si tiene el permiso 'delete comment'
        if (auth()->id() !== $comment->user_id && !auth()->user()->can('delete comment')) {
            abort(403, 'No tienes permiso para eliminar este comentario.');
        }

        $postId = $comment->post_id;
        $comment->delete();

        return redirect()->route('posts.show', $postId)
            ->with('success', 'Comentario eliminado.');
    }
}
