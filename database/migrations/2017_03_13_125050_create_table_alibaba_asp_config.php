<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAlibabaAspConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblAlibaba_ASP', function (Blueprint $table) {
            $table->increments('tblAlibaba_ASP_ID');
            $table->string('tblAlibaba_ASP_MID',16);
            $table->string('tblAlibaba_ASP_MerchantName',256);
            $table->string('tblAlibaba_ASP_SecondMerchantID',16);
            $table->string('tblAlibaba_ASP_SecondMerchantName',256);
            $table->string('tblAlibaba_ASP_TerminalID',16);
            $table->string('tblAlibaba_ASP_RefundMID');
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
        Schema::dropIfExists('tblAlibaba_ASP');
    }
}
