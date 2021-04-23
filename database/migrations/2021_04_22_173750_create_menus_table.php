<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('belong_id')->unsigned();
            $table->foreign('belong_id')->references('id')->on('menu_groups')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->index('title');
            $table->string('slug')->nullable();
            $table->string('item')->nullable();
            $table->string('class')->nullable();
            $table->string('target')->nullable();
            $table->string('attrs')->nullable();
            $table->text('body')->nullable();
            $table->string('status', 100)->default(config('add.page_statuses')[0] ?? 'inactive');
            $table->smallInteger('sort')->unsigned()->default('5000');
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
        Schema::dropIfExists('menus');
    }
}
