<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('gender_mbwin_codes', function (Blueprint $table) {
			$table->id();
			$table->foreignId('gender_configuration_id')->constrained('gender_configurations')->cascadeOnDelete();
			$table->string('mbwin_code')->unique();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('gender_mbwin_codes');
	}
};
