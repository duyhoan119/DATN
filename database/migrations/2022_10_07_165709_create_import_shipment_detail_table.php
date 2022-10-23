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
        Schema::create('import_shipment_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_shipment_id');
            $table->foreign('import_shipment_id')->references('id')->on('import_shipments');
            $table->integer('quantity');
            $table->float('import_price');
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
        Schema::dropIfExists('import_shipment_detail');
    }
};
