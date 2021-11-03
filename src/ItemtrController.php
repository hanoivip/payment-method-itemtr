<?php

namespace Hanoivip\PaymentMethodItemtr;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Hanoivip\Events\Payment\TransactionUpdated;

class ItemtrController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function callback(Request $request)
    {
        Log::debug("Itemtr callback data: " . print_r($request->all(), true));
        $SiparisID   = $request->input("siparisID");//mapping id
        $UserEmail   = $request->input("userEmail");
        $transId      = $request->input("userID");//~transaction id
        $userName    = $request->input("userName");
        $ReturnData  = $request->input("returnData");
        $Status      = $request->input("status");
        $OdemeKanali = $request->input("odemeKanali");
        $OdemeTutari = $request->input("odemeTutari");
        $NetKazanc   = $request->input("netKazanc");
        $ExtraData   = $request->input("extraData");//~my recharge id, package id, merchant id
        $Hash        = $request->input("hash");
        // 1. validate request
        // trans exists?
        $log = ItemtrTransaction::where('trans', $transId)->first();
        if (empty($log))
        {
            return response('e1');
        }
        // check integrity
        $apiKey = $log->key;
        $apiSecret = $log->secret;
        $hashKontrol = base64_encode(hash_hmac('sha256',$SiparisID."|".$transId."|".$ReturnData."|".$Status."|".$OdemeKanali."|".$OdemeTutari."|".$NetKazanc."|".$apiKey, $apiSecret, true));
        if ($hashKontrol != $Hash)
        {
            return response("e2");
        }
        // check processed?
        $status = $log->status;
        if (!empty($status))
        {
            return response("e3");
        }
        // 2. business process
        if ($Status == 'success')
        {
            event(new TransactionUpdated($log->trans));
            $log->amount = $OdemeTutari;
            $log->net_amount = $NetKazanc;
            $log->status = 1;
        }
        else 
        {
            $log->status = 2;
        }
        $log->save();
        return response("OK");
    }
}