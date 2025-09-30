<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CisaProduct extends Model
{
	use HasFactory;

	protected $fillable = [
		'cisa_code',
		'description',
		'type',
	];

	public function glCodes()
	{
		return $this->hasMany(CisaProductGlCode::class);
	}
}


