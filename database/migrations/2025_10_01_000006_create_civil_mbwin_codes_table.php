<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('civil_mbwin_codes', function (Blueprint $table) {
			$table->id();
			$table->foreignId('civil_configuration_id')->constrained('civil_configurations')->cascadeOnDelete();
			$table->string('mbwin_code')->unique();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('civil_mbwin_codes');
	}
};
