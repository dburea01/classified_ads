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
            $table->string('local_name');
            $table->string('english_name');
            $table->string('currency_id');
            $table->timestamps();

            $table->foreign('currency_id')->references('id')->on('currencies')->nullOnDelete();
        });

        // insert some countries
        $countries = [
            ['id' => 'FR', 'local_name' => 'France', 'english_name' => 'France', 'currency_id' => 'EUR'],
            ['id' => 'BE', 'local_name' => 'Belgique', 'english_name' => 'Belgium', 'currency_id' => 'EUR'],
            ['id' => 'IT', 'local_name' => 'Italia', 'english_name' => 'Italy', 'currency_id' => 'EUR'],
            ['id' => 'UK', 'local_name' => 'United Kingdom', 'english_name' => 'United Kingdom', 'currency_id' => 'GBP'],
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
