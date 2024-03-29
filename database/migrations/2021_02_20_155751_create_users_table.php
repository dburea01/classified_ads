<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organization_id')->nullable();
            $table->string('role_id');
            $table->string('user_state_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('password');
            $table->string('email_verification_code');
            $table->dateTime('email_verified_at')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnUpdate();
            $table->foreign('user_state_id')->references('id')->on('user_states')->cascadeOnUpdate();
            $table->unique(['organization_id', 'email']);
        });

        DB::statement('ALTER TABLE users ADD CONSTRAINT check_is_super_admin CHECK ( (organization_id IS NULL AND role_id = \'SUPERADMIN\') OR (organization_id IS NOT NULL) )');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
