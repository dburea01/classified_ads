<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSitesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sites', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->uuid('organization_id');
			$table->uuid('site_type_id');
			$table->string('country_id', 2);
			$table->string('name');
			$table->string('address1')->nullable();
			$table->string('address2')->nullable();
			$table->string('address3')->nullable();
			$table->string('zip_code')->nullable();
			$table->string('city');
			$table->string('status');
			$table->timestamps();

			$table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
			$table->foreign('country_id')->references('id')->on('countries')->nullOnDelete();
			$table->foreign('site_type_id')->references('id')->on('site_types')->nullOnDelete();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('sites');
	}
}
