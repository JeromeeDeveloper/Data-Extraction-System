<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CivilMbwinCode extends Model
{
	use HasFactory;

	protected $fillable = [
		'civil_configuration_id',
		'mbwin_code',
	];

	public function civilConfiguration()
	{
		return $this->belongsTo(CivilConfiguration::class);
	}
}
