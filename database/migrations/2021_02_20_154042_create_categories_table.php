<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

class CreateCategoriesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('categories', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->uuid('category_group_id');
			$table->tinyInteger('position');
			$table->string('name');
			$table->string('status');
			$table->string('created_by')->nullable();
			$table->string('updated_by')->nullable();
			$table->timestamps();

			$table->foreign('category_group_id')->references('id')->on('category_groups')->cascadeOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('categories');
	}
}
