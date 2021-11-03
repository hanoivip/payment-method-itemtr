<?php

namespace Hanoivip\PaymentMethodItemtr;

use Hanoivip\PaymentMethodContract\IPaymentMethod;
use Illuminate\Support\Facades\Auth;
use Exception;

class ItemtrMethod implements IPaymentMethod
{
    private $config;
    
    public function endTrans($trans)
    {}

    public function cancel($trans)
    {}

    public function beginTrans($trans)
    {
        $exists = ItemtrTransaction::where('trans', $trans->trans_id)->get();
        if ($exists->isNotEmpty())
            throw new Exception('ItemtrMethod transaction already exists');
        $apiKey = $this->config['key'];
        $apiSecret = $this->config['secret'];
        // 1.
        $log = new ItemtrTransaction();
        $log->trans = $trans->trans_id;
        $log->key = $apiKey;
        $log->secret = $apiSecret;
        $log->status = 0;//0 pending, 1 success, 2 failure
        $log->save();
        // 2. 
        $username = Auth::user()->getAuthIdentifierName();
        $session=[
            'userName'   => $username,
            'userID'	 => $trans->trans_id,
            'userEmail'  => "1@1.1",
            'userGsm'	 => "1"
        ];
        $token = urlencode(base64_encode(openssl_encrypt(json_encode($session), "AES-256-ECB", $apiSecret)));
        $paymentUrl = "https://www.itemtr.com/odeme?token=".$token."&key=".$apiKey;
        return new ItemtrSession($trans, $paymentUrl);
    }

    public function request($trans, $params)
    {
        return $this->query($trans);
    }

    public function query($trans)
    {
        $log = ItemtrTransaction::where()->first();
        return new ItemtrResult($log);
    }

    public function config($cfg)
    {
        $this->config = $cfg;
    }

    
}