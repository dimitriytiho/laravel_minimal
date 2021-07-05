<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLastDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     * Запустить миграцию:
     * php artisan migrate --path=/app/Services/LastData
     */
    public function up()
    {
        Schema::create('last_data', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->index();
            $table->bigInteger('element_id')->index();
            $table->string('table')->index();
            $table->json('data');
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
        Schema::dropIfExists('last_data');
    }
}
