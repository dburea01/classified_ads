<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->tinyInteger('position');
            $table->timestamps();
        });

        Role::insert(['id' => 'EMPLOYEE', 'name' => 'Employé', 'position' => 10]);
        Role::insert(['id' => 'ADMIN', 'name' => 'Administrateur', 'position' => 20]);
        Role::insert(['id' => 'SUPERADMIN', 'name' => 'Super administrateur', 'position' => 30]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
