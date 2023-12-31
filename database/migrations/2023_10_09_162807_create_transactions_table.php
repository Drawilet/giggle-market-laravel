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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->string("payer_name")->nullable();
            $table->unsignedBigInteger("payer_id")->nullable();
            $table->string("payer_type");

            $table->string("recipient_name")->nullable();
            $table->unsignedBigInteger("recipient_id")->nullable();
            $table->string("recipient_type");

            $table->decimal("amount");
            $table->string("description");

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
        Schema::dropIfExists('transactions');
    }
};
