<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CivilConfiguration extends Model
{
	use HasFactory;

	protected $fillable = [
		'civil_code',
		'civil_status',
	];
}
