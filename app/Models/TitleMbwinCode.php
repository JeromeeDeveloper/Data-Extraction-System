<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TitleMbwinCode extends Model
{
	use HasFactory;

	protected $fillable = [
		'title_configuration_id',
		'mbwin_code',
	];

	public function titleConfiguration()
	{
		return $this->belongsTo(TitleConfiguration::class);
	}
}
