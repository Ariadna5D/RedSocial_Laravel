<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Comment extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'post_id', 'reply', 'edited_by'];

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo{
        return $this->belongsTo(Post::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function isLikedByAuthUser(): bool
    {
        // Verificamos si hay un usuario autenticado y si su ID está en la colección de likes
        return $this->likes->where('user_id', auth()->id())->isNotEmpty();
    }
}
