<?php

namespace Hochstrasser\Wirecard\Model\Common;

use Hochstrasser\Wirecard\Model\Model;

class ShippingInformation extends Address
{
    protected $addressParameterPrefix = 'consumerShipping';
}
