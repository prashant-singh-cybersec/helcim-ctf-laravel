<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Customer
 * 
 * @property int $id
 * @property string $cust_name
 * @property string $email
 * @property int $organization_id
 * 
 * @property Organization $organization
 * @property Collection|Invoice[] $invoices
 *
 * @package App\Models
 */
class Customer extends Model
{
	protected $table = 'customer';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'organization_id' => 'int'
	];

	protected $fillable = [
		'id',
		'cust_name',
		'email',
		'organization_id'
	];

	public function organization()
	{
		return $this->belongsTo(Organization::class);
	}

	public function invoices()
	{
		return $this->hasMany(Invoice::class);
	}
}
