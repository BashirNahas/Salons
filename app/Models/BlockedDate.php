<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSalon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedDate extends Model
{
    /** @use HasFactory<\Database\Factories\BlockedDateFactory> */
    use HasFactory, BelongsToSalon;

    protected $fillable = [
        'salon_id',
        'date',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }
}
