<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::create('cisa_products', function (Blueprint $table) {
			$table->id();
			$table->string('cisa_code')->unique();
			$table->string('description');
			$table->enum('type', ['installment', 'non-installment']);
			$table->timestamps();
		});
	}

	public function down(): void
	{
		Schema::dropIfExists('cisa_products');
	}
};


