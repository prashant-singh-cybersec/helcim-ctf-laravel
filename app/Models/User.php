<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 * 
 * @property int $id
 * @property string $email
 * @property string $roles
 * @property string $password
 * @property bool $is_paid_user
 * @property int|null $mobile_number
 * @property string|null $image_path
 * @property int $organization_id
 * 
 * @property Organization $organization
 *
 * @package App\Models
 */
class User extends Authenticatable
{
	use HasApiTokens, Notifiable;

	protected $table = 'user';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'roles' => 'array',
		'is_paid_user' => 'bool',
		'mobile_number' => 'int',
		'organization_id' => 'int'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'id',
		'email',
		'roles',
		'password',
		'is_paid_user',
		'mobile_number',
		'image_path',
		'organization_id'
	];

	public function organization()
	{
		return $this->belongsTo(Organization::class);
	}

	public function featureRequests()
	{
		return $this->hasMany(FeatureRequest::class);
	}

}


