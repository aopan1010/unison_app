<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('未設定');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->text('introduction')->nullable();
            $table->integer('age')->nullable();
            $table->unsignedBigInteger('prefecture_id')->nullable();
            $table->tinyInteger('gender')->nullable()->comment('1:men 2:femail 3:non_selected');
            $table->string('profile_image')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('prefecture_id')
                ->references('id')
                ->on('prefectures')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
