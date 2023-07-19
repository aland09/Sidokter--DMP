<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoSppNoSpm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_dokumens', function (Blueprint $table) {
            $table->string('no_spm')->nullable()->after('keterangan');
            $table->string('no_spp')->nullable()->after('keterangan');
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
            $table->dropColumn('no_spm');
            $table->dropColumn('no_spp');
        });
    }
}
