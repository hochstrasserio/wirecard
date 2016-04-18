<?php

namespace Hochstrasser\Wirecard\Response;

use Hochstrasser\Wirecard\Model\Model;

interface WirecardResponseInterface
{
    /**
     * @return Model
     */
    function toObject();

    /**
     * @return array
     */
    function toArray();

    /**
     * @return bool
     */
    function hasErrors();

    /**
     * @return array
     */
    function getErrors();
}
