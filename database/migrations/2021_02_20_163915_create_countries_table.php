<?php

use App\Models\Country;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('countries', function (Blueprint $table) {
			$table->string('id', 2)->primary();
			$table->string('name');
			$table->timestamps();
		});

		// insert some countries
		$countries = [
			['id' => 'FR', 'name' => 'France'],
			['id' => 'BE', 'name' => 'Belgium'],
			['id' => 'IT', 'name' => 'Italy']
		];
		foreach ($countries as $country) {
			Country::create($country);
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('countries');
	}
}
