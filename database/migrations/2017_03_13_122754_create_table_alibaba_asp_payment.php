<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAlibabaAspPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblAlibaba_ASP_Payment', function (Blueprint $table) {
            // Request
            $table->increments('id');
            $table->string('ASP_TrxType',10)->default('');
            $table->integer('ASP_ConfigID');
            $table->string('ASP_Service',64)->default('');
            $table->string('ASP_Sign',32)->nullable();
            $table->string('ASP_SignType',5)->nullable();
            $table->string('ASP_PartnerID',16)->default('');
            $table->string('ASP_InputCharset',7)->default('');
            $table->string('ASP_AlipaySellerID',16)->default('');
            $table->integer('ASP_Quantity')->nullable();
            $table->string('ASP_TransName',256)->default('');
            $table->string('ASP_PartnerTransID',64)->default('');
            $table->string('ASP_Currency',8)->default('');
            $table->decimal('ASP_TransAmt',9,2);
            $table->string('ASP_BuyerIdentityCode',32)->default('');
            $table->string('ASP_IdentityCodeType',16)->default('');
            $table->string('ASP_TransCreateTime',16)->default('');
            $table->string('ASP_Memo',256)->default('');
            $table->string('ASP_BizProduct',256)->default('');
            $table->string('ASP_MCCID')->default('');
            // Response
            $table->char('ASP_IsSuccess',1)->default('');
            $table->string('ASP_ResultCode',32)->default('');
            $table->string('ASP_Error',48)->nullable();
            $table->string('ASP_AlipayBuyerLoginID',64)->nullable();
            $table->string('ASP_AlipayBuyerUserID',64)->default('');
            $table->string('ASP_AlipayTransID',64)->nullable();
            $table->string('ASP_AlipayPayTime',16)->nullable();
            $table->decimal('ASP_ExchangeRate',7,7)->nullable();
            $table->decimal('ASP_TransAmtCny',9,2)->nullable();
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
        Schema::dropIfExists('tblAlibaba_ASP_Payment');
    }
}
