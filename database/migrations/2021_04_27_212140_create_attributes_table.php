<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('property_id')->unsigned();
            $table->foreign('property_id')->references('id')->on('properties');
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->float('number')->nullable();
            $table->float('old')->nullable();
            $table->text('description')->nullable();
            $table->text('body')->nullable();
            $table->json('data')->nullable();
            $table->string('status')->default(config('add.statuses')[0] ?? 'inactive')->nullable();
            $table->enum('default', ['0', '1'])->default('0');
            $table->smallInteger('sort')->unsigned()->default('5000')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attributes');
    }
}
