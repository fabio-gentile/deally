<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoteDeals extends Model
{
    protected $table = 'votes_deals';
    use HasFactory;

    protected $fillable = [
        'deal_id',
        'user_id',
        'type',
    ];

    /**
     * Get the deal that owns the vote.
     */
    public function deal(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    /**
     * Get the user that owns the vote.
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
