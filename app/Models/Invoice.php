<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Invoice
 * 
 * @property int $id
 * @property int $customer_id
 * @property string $invoice_id
 * @property string $status
 * @property Carbon $date_issued
 * @property string $organization_details
 * @property string $logo
 * @property string $items
 * @property float $total_amount
 * @property string $token
 * @property int $organization_id
 * 
 * @property Customer $customer
 * @property Organization $organization
 *
 * @package App\Models
 */
class Invoice extends Model
{
	protected $table = 'invoice';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'customer_id' => 'int',
		'date_issued' => 'datetime',
		'total_amount' => 'float',
		'organization_id' => 'int',
		'items' => 'array'
	];

	protected $hidden = [
		'token'
	];

	protected $fillable = [
		'id',
		'customer_id',
		'invoice_id',
		'status',
		'date_issued',
		'organization_details',
		'logo',
		'items',
		'total_amount',
		'token',
		'organization_id'
	];

	public function customer()
	{
		return $this->belongsTo(Customer::class);
	}

	public function organization()
	{
		return $this->belongsTo(Organization::class);
	}
}
