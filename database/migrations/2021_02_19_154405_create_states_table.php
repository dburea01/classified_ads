<?php

use App\Models\UserState;
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
        Schema::create('user_states', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->tinyInteger('position');
            $table->timestamps();
        });

        UserState::insert(['id' => 'CREATED', 'name' => 'Créé, en attente de validation', 'position' => 10]);
        UserState::insert(['id' => 'VALIDATED', 'name' => 'Validé', 'position' => 20]);
        UserState::insert(['id' => 'BLOCKED', 'name' => 'bloqué', 'position' => 30]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_states');
    }
}
