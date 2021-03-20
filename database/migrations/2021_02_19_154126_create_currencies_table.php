<?php

use App\Models\Currency;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('symbol', 10);
            $table->timestamps();
        });

        // insert some currencies
        $currencies = [
            ['id' => 'EUR', 'symbol' => '€'],
            ['id' => 'USD', 'symbol' => '$'],
            ['id' => 'GBP', 'symbol' => '£']
        ];
        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
