<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('gender_configurations', function (Blueprint $table) {
			$table->renameColumn('gender_code', 'cisa_code');
			$table->renameColumn('gender', 'description');
		});
	}

	public function down(): void
	{
		Schema::table('gender_configurations', function (Blueprint $table) {
			$table->renameColumn('cisa_code', 'gender_code');
			$table->renameColumn('description', 'gender');
		});
	}
};
