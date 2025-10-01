<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('title_configurations', function (Blueprint $table) {
			$table->id();
			$table->string('title_code')->unique();
			$table->string('title');
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('title_configurations');
	}
};
