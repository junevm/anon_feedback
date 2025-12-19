<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feedback';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'content',
        'anonymous_token',
        'status',
        'moderation_note',
        'moderated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'moderated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the category that owns the feedback.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Check if feedback contains toxic keywords.
     *
     * @return bool
     */
    public function containsToxicContent(): bool
    {
        $toxicKeywords = [
            'kill', 'hate', 'stupid', 'idiot', 'fucking', 'shit', 
            'damn', 'bastard', 'asshole', 'bitch', 'moron',
        ];

        $content = strtolower($this->content);
        
        foreach ($toxicKeywords as $keyword) {
            if (str_contains($content, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Scope a query to only include pending feedback.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved feedback.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include flagged feedback.
     */
    public function scopeFlagged($query)
    {
        return $query->where('status', 'flagged');
    }
}
