<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payment;
use App\Transformers\PaymentTransformer;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

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


    public function sendRequest(Request $request, Payment $payment)
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
            'trans_amount' => 'required|numeric',
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

          // return response()->json('',201);
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
            'is_success' => 'required',
            'result_code' => 'required',
            'alipay_buyer_login_id' => 'required',
            'alipay_buyer_user_id' => 'required',
            'alipay_trans_id' => 'required',
            'alipay_pay_time' => 'required',
            'exchange_rate' => 'required',
            'trans_amount_cny' => 'required'
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
            'ASP_TransCreateTime' => $request->trans_create_time,
            'ASP_Memo' => $request->memo,
            'ASP_BizProduct' => $request->biz_product,
            'ASP_IsSuccess' => $request->is_success,
            'ASP_ResultCode' => $request->result_code,
            'ASP_Error' => $request->error,
            'ASP_AlipayBuyerLoginID' => $request->alipay_buyer_login_id,
            'ASP_AlipayBuyerUserID' => $request->alipay_buyer_user_id,
            'ASP_AlipayTransID' => $request->alipay_trans_id,
            'ASP_AlipayPayTime' => $request->alipay_pay_time,
            'ASP_ExchangeRate' => $request->exchange_rate,
            'ASP_TransAmtCny' => $request->trans_amount_cny
          ]);

          // return response()->json('',201);
    }

    public function sendQuery(Request $request)
    {
        $this->validate($request,[
          'partner' => 'required',
          'service' => 'required',
          'partner_trans_id' => 'required'
        ]);

        $arrValues = [];
        // construct pre-signed string
        foreach($request->json() as $index => $value)
        {
          array_push($arrValues,$index."=".$value);
        }
        // sort array by value ascending
        sort($arrValues);
        // implode the array to become string and using delimiter of '&'
        $imploded = implode($arrValues,'&');
        $key = 'aaczziabnm9w3sr23wu2zhevd7u8n1hx';
        $sign = md5($imploded.$key);
        $signtype = 'MD5';

        // push sign and signtype to array
        array_push($arrValues, 'sign='.$sign, 'sign_type='.$signtype);

        //implode final signed string
        $urlreq = implode($arrValues,'&');

        $client = new Client();
        $req = new GuzzleRequest('POST','https://openapi.alipaydev.com/gateway.do?'.$urlreq,[
          'connect_timeout' => '5',
          'timeout' => '5',
          'headers' => ['Accept' => 'application/xml', 'Content-Type' => 'text/xml']
        ]);

        $promise = $client->send($req);

        // $xml = simplexml_load_string($promise1->getBody()->getContents());
        // $json = json_encode($xml);
        // $array = json_decode($json,TRUE);

        return $promise->getBody()->getContents();
    }

    public function sendRefund(Request $request)
    {
      $this->validate($request,[
        'partner' => 'required',
        'service' => 'required',
        'partner_trans_id' => 'required'
      ]);

      $arrValues = [];

      // convert object type to array
      foreach($request->json() as $index => $value)
      {
        array_push($arrValues,$index.'='.$value);
      }

      // sort the array value ascending order
      sort($arrValues);

      // key from alipay
      $key = 'aaczziabnm9w3sr23wu2zhevd7u8n1hx';

      // implode Array
      $implode = implode($arrValues,'&');

      // sign pre-signed string
      $sign = md5($implode.$key);
      $signtype = 'MD5';

      array_push($arrValues, 'sign='.$sign, 'sign_type='.$signtype);
      // return response()->json('Can!', 200);

      $urlreq = implode($arrValues,'&');

      $client = new Client();
      $req = new GuzzleRequest('POST','https://openapi.alipaydev.com/gateway.do?'.$urlreq,[
        'connect_timeout' => '5',
        'timeout' => '5',
        'headers' => ['Accept' => 'application/xml', 'Content-Type' => 'text/xml']
      ]);

      $promise = $client->send($req);
      return $promise->getBody()->getContents();
    }

    public function sendCancel(Request $request)
    {
      $this->validate($request,[
        'partner' => 'required',
        'service' => 'required',
        'partner_trans_id' => 'required'
      ]);

      $arrValues = [];

      // convert object type to array
      foreach($request->json() as $index => $value)
      {
        array_push($arrValues,$index.'='.$value);
      }

      // sort the array value ascending order
      sort($arrValues);

      // key from alipay
      $key = 'aaczziabnm9w3sr23wu2zhevd7u8n1hx';

      // implode Array
      $implode = implode($arrValues,'&');

      // sign pre-signed string
      $sign = md5($implode.$key);
      $signtype = 'MD5';

      array_push($arrValues, 'sign='.$sign, 'sign_type='.$signtype);
      // return response()->json('Can!', 200);

      $urlreq = implode($arrValues,'&');

      $client = new Client();
      $req = new GuzzleRequest('POST','https://openapi.alipaydev.com/gateway.do?'.$urlreq,[
        'connect_timeout' => '5',
        'timeout' => '5',
        'headers' => ['Accept' => 'application/xml', 'Content-Type' => 'text/xml']
      ]);

      $promise = $client->send($req);
      return $promise->getBody()->getContents();
    }

    public function sendReverse(Request $request)
    {
      $this->validate($request,[
        'partner' => 'required',
        'service' => 'required',
        'partner_trans_id' => 'required'
      ]);

      $arrValues = [];

      // convert object type to array
      foreach($request->json() as $index => $value)
      {
        array_push($arrValues,$index.'='.$value);
      }

      // sort the array value ascending order
      sort($arrValues);

      // key from alipay
      $key = 'aaczziabnm9w3sr23wu2zhevd7u8n1hx';

      // implode Array
      $implode = implode($arrValues,'&');

      // sign pre-signed string
      $sign = md5($implode.$key);
      $signtype = 'MD5';

      array_push($arrValues, 'sign='.$sign, 'sign_type='.$signtype);
      // return response()->json('Can!', 200);

      $urlreq = implode($arrValues,'&');

      $client = new Client();
      $req = new GuzzleRequest('POST','https://openapi.alipaydev.com/gateway.do?'.$urlreq,[
        'connect_timeout' => '5',
        'timeout' => '5',
        'headers' => ['Accept' => 'application/xml', 'Content-Type' => 'text/xml']
      ]);

      $promise = $client->send($req);
      return $promise->getBody()->getContents();
    }
}
