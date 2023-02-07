<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('movies', function (Blueprint $table) {
			$table->integer('release_year');
			$table->integer('budget');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('movies', function (Blueprint $table) {
			$table->dropColumn('release_year');
			$table->dropColumn('budget');
		});
	}
};
