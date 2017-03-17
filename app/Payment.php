<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $fillable = [
      'ASP_BizProduct',
      'ASP_BuyerUserID',
      'ASP_Currency',
      'ASP_Error',
      'ASP_ExchangeRate',
      'ASP_InputCharset',
      'ASP_IsSuccess',
      'ASP_PartnerID' ,
      'ASP_PartnerTransID',
      'ASP_ResultCode',
      'ASP_Service',
      'ASP_Sign',
      'ASP_SignType',
      'ASP_AlipayTransID',
      'ASP_TransAmt',
      'ASP_TransAmtCny',
      'ASP_TrxType',
      'ASP_BizProduct',
      'ASP_BuyerIdentityCode',
      'ASP_AlipayBuyerLoginID',
      'ASP_IdentityCodeType',
      'ASP_Memo',
      'ASP_AlipayPayTime',
      'ASP_Quantity',
      'ASP_TransCreateTime',
      'ASP_TransName',
      'ASP_ConfigID',
      'ASP_AlipaySellerID',
      'ASP_MCCID',
      'ASP_AlipayBuyerUserID'
    ];
    public $timestamps = false;
    protected $table = 'tblAlibaba_ASP_Payment';
}
