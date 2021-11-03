<?php

namespace Hanoivip\PaymentMethodItemtr;

use Hanoivip\PaymentMethodContract\IPaymentResult;


class ItemtrResult implements IPaymentResult
{
    /**
     * 
     * @var ItemtrTransaction
     */
    private $log;
    
    public function __construct($log)
    {
        $this->log = $log;
    }
    
    public function getDetail()
    {}

    public function toArray()
    {
        $arr = [];
        $arr['detail'] = $this->getDetail();
        $arr['amount'] = $this->getAmount();
        $arr['isPending'] = $this->isPending();
        $arr['isFailure'] = $this->isFailure();
        $arr['isSuccess'] = $this->isSuccess();
        $arr['trans'] = $this->getTransId();
        return $arr;
    }

    public function isPending()
    {
        return $this->log->status == 0;
    }

    public function isFailure()
    {
        return $this->log->status == 2;
    }

    public function getTransId()
    {
        return $this->log->trans;
    }

    public function isSuccess()
    {
        return $this->log->status == 1;
    }

    public function getAmount()
    {
        if ($this->isSuccess())
            return $this->log->amount;
        return 0;
    }
   
}
