<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('civil_configurations', function (Blueprint $table) {
			$table->id();
			$table->string('civil_code')->unique();
			$table->string('civil_status');
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('civil_configurations');
	}
};
