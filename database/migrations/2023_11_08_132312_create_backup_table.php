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
        $date = date("Y-m-d");
        Schema::create('status_bayar'. $date , function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nim', 14);
            $table->string('nama', 100);
            $table->char('akdm_stat', 2);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $date = date("Y-m-d");
        Schema::dropIfExists('status_bayar'. $date);
    }
};
