<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenderMbwinCode extends Model
{
	use HasFactory;

	protected $fillable = [
		'gender_configuration_id',
		'mbwin_code',
	];

	public function genderConfiguration()
	{
		return $this->belongsTo(GenderConfiguration::class);
	}
}
