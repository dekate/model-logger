<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('model_loggers', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->dateTime('date');
      $table->unsignedBigInteger('user_id')->nullable();
      $table->string('model')->nullable();
      $table->string('reference')->nullable();
      $table->enum('operation', ['C', 'U', 'D'])->comment("C (Create) U (Update) D (delete)");
      $table->text('before')->nullable();
      $table->text('after')->nullable();
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
    Schema::dropIfExists('model_loggers');
  }
};
