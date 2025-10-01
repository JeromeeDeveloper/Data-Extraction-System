<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('title_mbwin_codes', function (Blueprint $table) {
			$table->id();
			$table->foreignId('title_configuration_id')->constrained('title_configurations')->cascadeOnDelete();
			$table->string('mbwin_code')->unique();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('title_mbwin_codes');
	}
};
