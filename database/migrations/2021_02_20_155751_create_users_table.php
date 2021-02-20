<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->uuid('organisation_id');
			$table->boolean('is_admin')->default('false');
			$table->string('first_name');
			$table->string('last_name');
			$table->string('email');
			$table->string('created_by')->nullable();
			$table->string('upated_by')->nullable();
			$table->timestamps();

			$table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('cascade');
			$table->unique(['organisation_id', 'email']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}
}
