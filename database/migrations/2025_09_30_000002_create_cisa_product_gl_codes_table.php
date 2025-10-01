<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('cisa_product_gl_codes', function (Blueprint $table) {
			$table->id();
			$table->foreignId('cisa_product_id')->constrained('cisa_products')->cascadeOnDelete();
			$table->string('gl_code')->unique();
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('cisa_product_gl_codes');
	}
};


