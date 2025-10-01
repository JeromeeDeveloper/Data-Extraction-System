<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CisaProductGlCode extends Model
{
	use HasFactory;

	protected $fillable = [
		'cisa_product_id',
		'gl_code',
	];

	public function cisaProduct()
	{
		return $this->belongsTo(CisaProduct::class, 'cisa_product_id');
	}
}


