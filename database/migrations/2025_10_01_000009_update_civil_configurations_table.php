<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('civil_configurations', function (Blueprint $table) {
			$table->renameColumn('civil_code', 'cisa_code');
			$table->renameColumn('civil_status', 'description');
		});
	}

	public function down(): void
	{
		Schema::table('civil_configurations', function (Blueprint $table) {
			$table->renameColumn('cisa_code', 'civil_code');
			$table->renameColumn('description', 'civil_status');
		});
	}
};
