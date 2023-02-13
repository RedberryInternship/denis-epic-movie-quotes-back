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
		Schema::create('notifications', function (Blueprint $table) {
			$table->id();
			$table->boolean('is_read')->default(false);
			$table->boolean('is_comment');
			$table->foreignId('from_user_id')->constrained('users')->cascadeOnDelete();
			$table->foreignId('to_user_id')->constrained('users')->cascadeOnDelete();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('notifications');
	}
};
