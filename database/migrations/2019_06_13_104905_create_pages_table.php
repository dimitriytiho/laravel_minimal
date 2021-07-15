<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->index('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('body')->nullable();
            $table->string('status')->default(config('add.statuses')[0] ?? 'inactive')->nullable();
            $table->smallInteger('sort')->unsigned()->default('5000')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // For lazychaser/laravel-nestedset
            $table->bigInteger('_lft')->unsigned()->default('0');
            $table->bigInteger('_rgt')->unsigned()->default('0');
            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->index(['_lft', '_rgt', 'parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
