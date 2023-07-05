<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilesDokumen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_dokumens', function (Blueprint $table) {
            $table->string('file_dokumen')->nullable()->after('no_box');
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
            $table->dropColumn('file_dokumen');
        });
    }
}
