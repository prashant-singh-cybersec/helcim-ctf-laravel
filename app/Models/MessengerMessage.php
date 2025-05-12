<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MessengerMessage
 * 
 * @property int $id
 * @property string $body
 * @property string $headers
 * @property string $queue_name
 * @property Carbon $created_at
 * @property Carbon $available_at
 * @property Carbon|null $delivered_at
 *
 * @package App\Models
 */
class MessengerMessage extends Model
{
	protected $table = 'messenger_messages';
	public $timestamps = false;

	protected $casts = [
		'available_at' => 'datetime',
		'delivered_at' => 'datetime'
	];

	protected $fillable = [
		'body',
		'headers',
		'queue_name',
		'available_at',
		'delivered_at'
	];
}
