<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassifiedAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classified_ads', function (Blueprint $table) {
            $table->uuid('id');
            $table->uuid('organization_id');
            $table->uuid('category_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->uuid('site_id')->nullable();
            $table->string('ads_status_id', 10);
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('organization_id')->references('id')->on('organizations')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
            $table->foreign('site_id')->references('id')->on('sites')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classified_ads');
    }
}
