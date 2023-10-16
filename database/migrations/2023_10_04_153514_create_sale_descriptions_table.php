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
        Schema::create('sale_descriptions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("sale_id");
            $table->foreign("sale_id")->references("id")->on("sales");

            $table->unsignedBigInteger("store_id");
            $table->foreign("store_id")->references("id")->on("stores");

            $table->string("description");
            $table->integer("quantity");
            $table->decimal("price");

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
        Schema::dropIfExists('sale_description');
    }
};
