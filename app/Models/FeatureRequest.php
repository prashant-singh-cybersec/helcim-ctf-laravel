<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * Class FeatureRequest
 * 
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string|null $details
 * @property string $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property User $user
 */
class FeatureRequest extends Model
{
    protected $table = 'feature_requests';

    protected $fillable = [
        'user_id',
        'title',
        'details',
        'status',
        'attachment' // ← NEW
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
