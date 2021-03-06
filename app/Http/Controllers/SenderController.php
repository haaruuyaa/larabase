<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class SenderController extends Controller
{
    //

    public function sendPay(Request $request)
    {
        // Data generated by system
        $transcreatetime = date('Ymdhis');
        $mcc_code = $request->secondary_merchant_industry;
        // Get config data by ID
        $configid = $request->config_id;
        $configData =  DB::table('tblAlibaba_ASP')
                        ->select(
                          'tblAlibaba_ASP_SecondMerchantID as secondary_merchant_id','tblAlibaba_ASP_SecondMerchantName as secondary_merchant_name',
                          'tblAlibaba_ASP_TerminalID as terminal_id','tblAlibaba_ASP_MCC_Code as secondary_merchant_industry')
                        ->where('tblAlibaba_ASP_ID','=',$configid)
                        ->first();
        // convert to json string
        $json = json_encode($configData);

        $arrValues = [];
        // construct pre-signed string
        foreach($request->json() as $index => $value)
        {
            if($index == 'secondary_merchant_industry' OR $index == 'config_id')
            {
              unset($index);
            } else {
              array_push($arrValues,$index."=".$value);
            }

        }
        // push some data to array
        array_push($arrValues,'trans_create_time='.$transcreatetime);
        // array_push($arrValues,'trans_create_time='.$transcreatetime,'extend_info='.$json);
        // sort array by value ascending
        sort($arrValues);
        // implode the array to become string and using delimiter of '&'
        $imploded = implode($arrValues,'&');
        // sign the pre-signed string
        $key = 'aaczziabnm9w3sr23wu2zhevd7u8n1hx';
        $sign = md5($imploded.$key);
        $signtype = 'MD5';

        array_push($arrValues, 'sign='.$sign, 'sign_type='.$signtype);
        $urlreq = implode($arrValues,'&');

        $client = new Client();
        $req = new GuzzleRequest('POST','https://openapi.alipaydev.com/gateway.do?'.$urlreq,[
          'connect_timeout' => '5',
          'timeout' => '5',
          'headers' => ['Accept' => 'application/xml', 'Content-Type' => 'text/xml']
        ]);
        $promise1 = $client->sendAsync($req)->then(function($response) use ($request,$client,$configid,$transcreatetime,$sign,$signtype,$urlreq){
          // echo $response->getBody()->getContents();
            // save data request
            if($response->getStatusCode() == 200)
            {

                  $xml = simplexml_load_file('https://openapi.alipaydev.com/gateway.do?'.$urlreq);
                  $json = json_encode($xml);
                  $array = json_decode($json,TRUE);

                  $responseData = $array['response']['alipay'];
                  $requestData = $array['request'];

                  if($responseData['result_code'] == 'SUCCESS')
                  {
                    $promise = $client->postAsync('http://dev17.revpay.com.my:8000/api/request',[
                        'timeout' => '5',
                        'headers' => [
                          'Content-Type' => 'application/json',
                          'Accept' => 'application/json',
                        ],
                        'json' => [
                          "_input_charset" => $request->_input_charset,
                          "config_id" => $configid,
                          "service" => $request->service,
                          "partner" => $request->partner,
                          "alipay_seller_id" => $request->alipay_seller_id,
                          "partner_trans_id" => $request->partner_trans_id,
                          "currency" => $request->currency,
                          "trans_amount" => $request->trans_amount,
                          "trans_name" => $request->trans_name,
                          "buyer_identity_code" => $request->buyer_identity_code,
                          "identity_code_type" => $request->identity_code_type,
                          "memo" => $request->memo,
                          "secondary_merchant_industry" => $request->secondary_merchant_industry,
                          "biz_product" => $request->biz_product,
                          "trans_create_time" => $transcreatetime,
                          "sign" => $sign,
                          "sign_type" => $signtype
                        ]
                    ])->then(function($response) use ($request,$configid,$client,$transcreatetime,$array,$responseData){

                      if($response->getStatusCode() == 200)
                      {
                        $promise2 = $client->post('http://dev17.revpay.com.my:8000/api/response',
                          [
                            'timeout' => '5',
                            'headers' => [
                              'Content-Type' => 'application/json',
                              'Accept' => 'application/json',
                            ],
                            'json' => [
                                "_input_charset" => $request->_input_charset,
                                "config_id" => $configid,
                                "service" => $request->service,
                                "partner" => $request->partner,
                                "alipay_seller_id" => $request->alipay_seller_id,
                                "partner_trans_id" => $request->partner_trans_id,
                                "currency" => $request->currency,
                                "trans_amount" => $request->trans_amount,
                                "trans_name" => $request->trans_name,
                                "buyer_identity_code" => $request->buyer_identity_code,
                                "identity_code_type" => $request->identity_code_type,
                                "memo" => $request->memo,
                                "secondary_merchant_industry" => $request->secondary_merchant_industry,
                                "biz_product" => $request->biz_product,
                                "trans_create_time" => $transcreatetime,
                                "sign" => $array['sign'],
                                "sign_type" => $array['sign_type'],
                                "is_success" => $array['is_success'],
                                "result_code" => $responseData['result_code'],
                                "error" => (isset($responseData['error'])) ? $responseData['error'] : NULL,
                                "alipay_buyer_login_id" => $responseData['alipay_buyer_login_id'],
                                "alipay_buyer_user_id" => $responseData['alipay_buyer_user_id'],
                                "alipay_trans_id" => $responseData['alipay_trans_id'],
                                "alipay_pay_time" => $responseData['alipay_pay_time'],
                                "exchange_rate" => $responseData['exchange_rate'],
                                "trans_amount_cny" => $responseData['trans_amount_cny']
                            ]
                          ]
                        );

                        // echo $response->getBody()->getContents();
                      } else {
                        echo 'Failed to save the request';
                      }

                    });
                    $promise->wait();
                    echo $response->getBody()->getContents();
                  } else {
                    echo $response->getBody()->getContents();
                  }

              } else {
                $service = 'alipay.acquire.overseas.query';
                $sendQuery = $client->postAsync('http://dev17.revpay.com.my:8000/api/query',[
                    'timeout' => '5',
                    'headers' => [
                      'Content-Type' => 'application/json',
                      'Accept' => 'application/json',
                    ],
                    'json' => [
                      "_input_charset" => $request->_input_charset,
                      "service" => $service,
                      "partner" => $request->partner,
                      "partner_trans_id" => $request->partner_trans_id
                    ]
                ])->then(function($response){
                  echo $response->getBody()->getContents();
                });
                $sendQuery->wait();
              }
        });

        $promise1->wait();

    }

}
