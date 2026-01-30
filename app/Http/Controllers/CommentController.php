<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Procesa el Like en un comentario usando relación polimórfica.
     */
    public function like(Request $request, Comment $comment): RedirectResponse
    {

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
        if (auth()->id() !== $comment->user_id && ! auth()->user()->can('edit comment')) {
            abort(403);
        }

        return view('social.comment-edit', compact('comment'));
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update(array_merge($request->validated(), [
            'edited_by' => auth()->user()->getRoleNames()->first() ?? 'Usuario',
        ]));

        return redirect()->route('posts.show', $comment->post_id)
            ->with('success', 'Comentario actualizado.');
    }

    /**
     * Guarda un nuevo comentario.
     */
    public function store(StoreCommentRequest $request)
    {
        $request->user()->comments()->create($request->validated());

        return back()->with('success', 'Comentario añadido.');
    }

    /**
     * Elimina el comentario de forma segura.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        // SEGURIDAD MANUAL:
        // Solo puede borrarlo si es el dueño O si tiene el permiso 'delete comment'
        if (auth()->id() !== $comment->user_id && ! auth()->user()->can('delete comment')) {
            abort(403, 'No tienes permiso para eliminar este comentario.');
        }

        $postId = $comment->post_id;
        $comment->delete();

        return redirect()->route('posts.show', $postId)
            ->with('success', 'Comentario eliminado.');
    }
}
