<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryGroupsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('category_groups', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->uuid('organisation_id');
			$table->tinyInteger('position');
			$table->string('name');
			$table->string('status');
			$table->string('created_by')->nullable();
			$table->string('updated_by')->nullable();
			$table->timestamps();

			$table->foreign('organisation_id')->references('id')->on('organisations')->cascadeOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('category_groups');
	}
}
