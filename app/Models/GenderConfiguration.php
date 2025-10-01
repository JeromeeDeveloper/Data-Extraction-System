<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenderConfiguration extends Model
{
	use HasFactory;

	protected $fillable = [
		'cisa_code',
		'description',
	];

	public function mbwinCodes()
	{
		return $this->hasMany(GenderMbwinCode::class);
	}
}
