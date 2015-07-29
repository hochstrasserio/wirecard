<?php

namespace Hochstrasser\Wirecard\Model\Seamless;

use Hochstrasser\Wirecard\Model\Model;

class DataStorageInitResult extends Model
{
    /**
     * Unique reference of the data storage for a consumer
     *
     * @return string
     */
    function getStorageId()
    {
        return $this->getParam('storageId');
    }

    /**
     * URL to a JavaScript resource which have to be included for using the
     * storage operations of the data storage
     *
     * @return string
     */
    function getJavascriptUrl()
    {
        return $this->getParam('javascriptUrl');
    }
}
