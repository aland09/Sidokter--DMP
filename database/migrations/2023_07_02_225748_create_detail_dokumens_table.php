<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailDokumensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_dokumens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dokumen_id')->nullable();
            $table->string('kode_klasifikasi')->nullable();
            $table->text('uraian')->nullable();
            $table->dateTime('tanggal_surat')->nullable();
            $table->string('jumlah_satuan')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('jenis_naskah_dinas')->nullable();
            $table->string('no_surat')->nullable();
            $table->string('pejabat_penandatangan')->nullable();
            $table->string('unit_pengolah')->nullable();
            $table->integer('kurun_waktu')->nullable();
            $table->string('no_box')->nullable();
            $table->string('tkt_perk')->nullable();
            $table->string('file_dokumen')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('detail_dokumens');
    }
}
