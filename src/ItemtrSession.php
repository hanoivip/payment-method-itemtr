<?php

namespace Hanoivip\PaymentMethodItemtr;

use Hanoivip\PaymentMethodContract\IPaymentSession;


class ItemtrSession implements IPaymentSession
{
    // stdObject, Transaction eloquent
    private $trans;
    
    private $paymentUrl;
    
    public function __construct($trans, $url)
    {
        $this->trans = $trans;
        $this->paymentUrl = $url;
    }
    
    public function getSecureData()
    {
        return [];
    }

    public function getGuide()
    {
        return __('hanoivip::itemtr.guide');
    }

    public function getTransId()
    {
        return $this->trans->trans_id;
    }

    public function getData()
    {
        return ['paymentUrl' => $this->paymentUrl];
    }

}
