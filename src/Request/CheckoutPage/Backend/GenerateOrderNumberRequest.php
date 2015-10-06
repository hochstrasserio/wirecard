<?php

namespace Hochstrasser\Wirecard\Request\CheckoutPage\Backend;

class GenerateOrderNumberRequest extends AbstractBackendRequest
{
    protected $operation = 'generateOrderNumber';
    protected $requiredParameters = [];
    protected $fingerprintOrder = [];
    // protected $resultClass = 'Hochstrasser\Wirecard\Model\Seamless\Frontend\DataStorageInitResult';
}
