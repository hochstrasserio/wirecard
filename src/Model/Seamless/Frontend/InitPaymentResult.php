<?php

namespace Hochstrasser\Wirecard\Model\Seamless\Frontend;

use Hochstrasser\Wirecard\Model\Model;

class InitPaymentResult extends Model
{
    function getRedirectUrl()
    {
        return $this->getParam('redirectUrl');
    }
}
