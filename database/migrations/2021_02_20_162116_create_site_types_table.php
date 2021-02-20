<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteTypesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('site_types', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->uuid('organisation_id');
			$table->string('name');
			$table->string('status');
			$table->string('created_by')->nullable();
			$table->string('updated_by')->nullable();
			$table->timestamps();

			$table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('site_types');
	}
}
