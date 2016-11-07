<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->text('content');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('edited_at')->nullable();
            $table->integer('counter_comments')->unsigned()->default(0);
            $table->integer('counter_likes')->unsigned()->default(0);

            $table->index('user_id');

        });

        Schema::table('articles', function (Blueprint $table) {

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
