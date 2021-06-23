<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->index('title');
            $table->string('slug')->nullable();
            $table->index('slug');
            $table->float('number')->nullable();
            $table->float('old')->nullable();
            $table->text('description')->nullable();
            $table->text('body')->nullable();
            $table->string('status')->default(config('add.statuses')[0] ?? 'inactive');
            $table->enum('default', ['0', '1'])->default('0');
            $table->smallInteger('sort')->unsigned()->default('5000');
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
        Schema::dropIfExists('properties');
    }
}
