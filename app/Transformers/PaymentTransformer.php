<?php
namespace App\Transformers;

use App\Payment;
use League\Fractal\TransformerAbstract;


class PaymentTransformer extends TransformerAbstract {


      public function transform(Payment $payment)
      {
          return [
            'trx_type' => $payment->ASP_TrxType,
            'config_id' => $payment->ASP_ConfigID,
            'service' => $payment->ASP_Service,
            'sign' => $payment->ASP_Sign ,
            'sign_type' => $payment->ASP_SignType ,
            'partner' => $payment->ASP_PartnerID ,
            '_input_charset' => $payment->ASP_InputCharset ,
            'alipay_seller_id' => $payment->ASP_AlipaySellerID ,
            'quantity' => $payment->ASP_Quantity ,
            'trans_name' => $payment->ASP_TransName,
            'partner_trans_id' => $payment->ASP_PartnerTransID ,
            'currency' => $payment->ASP_Currency,
            'trans_amount' => $payment->ASP_TransAmt,
            'buyer_identity_code' => $payment->ASP_BuyerIdentityCode ,
            'identity_code_type' => $payment->ASP_IdentityCodeType,
            'trans_create_time' => $payment->ASP_TransCreateTime,
            'memo' => $payment->ASP_Memo,
            'biz_product' => $payment->ASP_BizProduct
          ];
      }

}


 ?>
