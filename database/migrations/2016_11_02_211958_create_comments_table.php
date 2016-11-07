<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('article_id')->unsigned();
            $table->integer('comment_id')->unsigned()->nullable();
            $table->text('content');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('edited_at')->nullable();
            $table->integer('counter_comments')->unsigned()->default(0);
            $table->integer('counter_likes')->unsigned()->default(0);
            $table->boolean('was_removed')->default(0);

            $table->index('user_id');
            $table->index('article_id');
            $table->index('comment_id');

        });

        Schema::table('comments', function (Blueprint $table) {

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('set null')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
