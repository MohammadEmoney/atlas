<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stage extends Model
{
    use HasFactory;

    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
    protected $fillable = [
        'name',
        'role',
        'order',
        'min_days',
        'next_stage_id',
    ];

    /**
     * Get the nextStage that owns the Stage
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nextStage(): BelongsTo
    {
        return $this->belongsTo(Stage::class, 'next_stage_id');
    }
}
