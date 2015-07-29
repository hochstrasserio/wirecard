<?php

namespace Hochstrasser\Wirecard\Response;

interface WirecardResponseInterface
{
    function toObject();
    function toArray();
    function hasErrors();
    function getErrors();
}
