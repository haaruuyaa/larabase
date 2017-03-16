<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment;
use App\Transformers\PaymentTransformer;

class RequestController extends Controller
{
    //

    public function request(Request $req, Payment $payment)
    {
        $payment = $payment->all();

        $response = fractal()
        ->collection($payment)
        ->transformWith(new PaymentTransformer)
        ->toArray();

        return response()->json($response,201);
    }


    public function saveData(Request $request, Payment $payment)
    {

          $this->validate($request,[
            'config_id' => 'required',
            'service' => 'required',
            'sign' => 'required' ,
            'sign_type' => 'required' ,
            'partner' => 'required' ,
            'alipay_seller_id' => 'required',
            'trans_name' => 'required',
            'partner_trans_id' => 'required' ,
            'currency' => 'required',
            'trans_amount' => 'required',
            'buyer_identity_code' => 'required' ,
            'identity_code_type' => 'required',
            'trans_create_time' => 'required',
            'biz_product' => 'required'
          ]);

          $payment->create([
            'ASP_TrxType' => "REQ",
            'ASP_ConfigID' => $request->config_id,
            'ASP_Service' => $request->service,
            'ASP_Sign' => $request->sign ,
            'ASP_SignType' => $request->sign_type ,
            'ASP_PartnerID' => $request->partner ,
            'ASP_InputCharset' => $request->_input_charset ,
            'ASP_AlipaySellerID' => $request->alipay_seller_id ,
            'ASP_Quantity' => $request->quantity ,
            'ASP_TransName' => $request->trans_name,
            'ASP_PartnerTransID' => $request->partner_trans_id ,
            'ASP_Currency' => $request->currency,
            'ASP_TransAmt' => $request->trans_amount,
            'ASP_BuyerIdentityCode' => $request->buyer_identity_code ,
            'ASP_IdentityCodeType' => $request->identity_code_type,
            'ASP_TransCreateTime' => date('Ymdhis'),
            'ASP_Memo' => $request->memo,
            'ASP_BizProduct' => $request->biz_product
          ]);

          return redirect()->action('RequestController@request');
    }

    public function saveResponse(Request $request, Payment $payment)
    {

          $this->validate($request,[
            'config_id' => 'required',
            'service' => 'required',
            'sign' => 'required' ,
            'sign_type' => 'required' ,
            'partner' => 'required' ,
            'alipay_seller_id' => 'required',
            'trans_name' => 'required',
            'partner_trans_id' => 'required' ,
            'currency' => 'required',
            'trans_amount' => 'required',
            'buyer_identity_code' => 'required' ,
            'identity_code_type' => 'required',
            'trans_create_time' => 'required',
            'biz_product' => 'required',
            'alipay_buyer_user_id' => 'required',
            'alipay_buyer_login_id' => 'required',
            'alipay_trans_id' => 'required',
            'alipay_pay_time' => 'required'
          ]);

          $payment->create([
            'ASP_TrxType' => 'RES',
            'ASP_ConfigID' => $request->config_id,
            'ASP_Service' => $request->service,
            'ASP_Sign' => $request->sign ,
            'ASP_SignType' => $request->sign_type ,
            'ASP_PartnerID' => $request->partner ,
            'ASP_InputCharset' => $request->_input_charset ,
            'ASP_AlipaySellerID' => $request->alipay_seller_id ,
            'ASP_Quantity' => $request->quantity ,
            'ASP_TransName' => $request->trans_name,
            'ASP_PartnerTransID' => $request->partner_trans_id ,
            'ASP_Currency' => $request->currency,
            'ASP_TransAmt' => $request->trans_amount,
            'ASP_BuyerIdentityCode' => $request->buyer_identity_code ,
            'ASP_IdentityCodeType' => $request->identity_code_type,
            'ASP_TransCreateTime' => date('Ymdhis'),
            'ASP_Memo' => $request->memo,
            'ASP_BizProduct' => $request->biz_product,
            'ASP_IsSuccess' => $request->is_success,
            'ASP_ResultCode' => $request->result_code,
            'ASP_Error' => $request->error,
            'ASP_AlipayBuyerLoginID' => $request->alipay_buyer_login_id,
            'ASP_AlipayBuyerUserID' => $request->alipay_buyer_user_id,
            'ASP_AlipayTransID' => $request->alipay_trans_id,
            'ASP_PayTime' => $request->alipay_pay_time,
            'ASP_ExchangeRate' => $request->exchange_rate,
            'ASP_TransAmtCny' => $request->trans_amount_cny
          ]);

          return redirect()->action('RequestController@request');
    }

}
