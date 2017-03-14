<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMcccode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblMCC', function (Blueprint $table) {
            $table->increments('MCC_ID');
            $table->string('MCC_Scheme_ID',16);
            $table->string('MCC_Code',10);
            $table->string('MCC_Desc',256);
            $table->string('LastModifiedUser',50);
            $table->string('LastModifiedVersion',10);
            $table->datetime('CreationDT');
            $table->datetime('LastUpdateDT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblMCC');
    }
}
