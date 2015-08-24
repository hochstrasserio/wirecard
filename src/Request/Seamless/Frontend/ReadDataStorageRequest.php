<?php

namespace Hochstrasser\Wirecard\Request\Seamless\Frontend;

use Hochstrasser\Wirecard\Request\AbstractWirecardRequest;

class ReadDataStorageRequest extends AbstractWirecardRequest
{
    protected $requiredParameters = ['storageId'];
    protected $fingerprintOrder = ['storageId'];
    protected $endpoint = 'https://checkout.wirecard.com/seamless/dataStorage/read';
    protected $resultClass = 'Hochstrasser\Wirecard\Model\Seamless\Frontend\DataStorageReadResult';

    static function withStorageId($storageId)
    {
        $request = new static();
        $request->setStorageId($storageId);

        return $request;
    }

    function setStorageId($storageId)
    {
        return $this->addParam('storageId', $storageId);
    }
}
