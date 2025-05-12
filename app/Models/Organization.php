<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Organization
 * 
 * @property int $id
 * @property int $org_id
 * @property string $org_name
 * 
 * @property Collection|User[] $users
 * @property Collection|Invoice[] $invoices
 * @property Collection|Customer[] $customers
 *
 * @package App\Models
 */
class Organization extends Model
{
	protected $table = 'organization';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'org_id' => 'int'
	];

	protected $fillable = [
		'org_id',
		'org_name'
	];

	public function users()
	{
		return $this->hasMany(User::class);
	}

	public function invoices()
	{
		return $this->hasMany(Invoice::class);
	}

	public function customers()
	{
		return $this->hasMany(Customer::class);
	}
}
