<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenderConfiguration extends Model
{
	use HasFactory;

	protected $fillable = [
		'gender_code',
		'gender',
	];
}
