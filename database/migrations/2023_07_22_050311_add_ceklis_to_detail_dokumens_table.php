<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCeklisToDetailDokumensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_dokumens', function (Blueprint $table) {
            $table->string('ceklis_seluruh_detail')->nullable();
            $table->dateTime('tanggal_ceklis_seluruh_detail')->nullable();
            $table->string('ceklis_satuan_detail')->nullable();
            $table->dateTime('tanggal_ceklis_satuan_detail')->nullable();
            $table->string('ceklis_kerja_detail')->nullable();
            $table->dateTime('tanggal_ceklis_kerja_detail')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detail_dokumens', function (Blueprint $table) {
            //
        });
    }
}
