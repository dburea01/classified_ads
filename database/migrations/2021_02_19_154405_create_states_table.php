<?php

use App\Models\State;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->tinyInteger('position');
            $table->timestamps();
        });

        State::insert(['id' => 'CREATED', 'name' => 'Créé, en attente de validation', 'position' => 10]);
        State::insert(['id' => 'VALIDATED', 'name' => 'Validé', 'position' => 20]);
        State::insert(['id' => 'BLOCKED', 'name' => 'bloqué', 'position' => 30]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('states');
    }
}
