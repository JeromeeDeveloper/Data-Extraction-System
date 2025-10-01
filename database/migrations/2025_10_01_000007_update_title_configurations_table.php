<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('title_configurations', function (Blueprint $table) {
			$table->renameColumn('title_code', 'cisa_code');
			$table->renameColumn('title', 'description');
		});
	}

	public function down(): void
	{
		Schema::table('title_configurations', function (Blueprint $table) {
			$table->renameColumn('cisa_code', 'title_code');
			$table->renameColumn('description', 'title');
		});
	}
};
