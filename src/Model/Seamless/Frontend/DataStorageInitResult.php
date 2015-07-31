<?php

namespace Hochstrasser\Wirecard\Model\Seamless\Frontend;

use Hochstrasser\Wirecard\Model\Model;

class DataStorageInitResult extends Model
{
    /**
     * Unique reference to the data storage of a consumer
     *
     * @return string
     */
    function getStorageId()
    {
        return $this->getParam('storageId');
    }

    /**
     * URL to a JavaScript resource which has to be included for storing data in
     * the data storage
     *
     * @return string
     */
    function getJavascriptUrl()
    {
        return $this->getParam('javascriptUrl');
    }
}
