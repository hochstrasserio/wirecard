<?php

namespace Hochstrasser\Wirecard;

class Context implements \Serializable
{
    const PCI3 = 'pci3';

    private $customerId;
    private $secret;
    private $language;
    private $shopId;
    private $javascriptScriptVersion;
    private $userAgent = 'hochstrasser/wirecard';

    /**
     * Constructor
     *
     * @param string $customerId Unique ID of merchant
     * @param string $secret Secret key for fingerprinting
     * @param string $language Language for returned texts and error messages
     * @param string $shopId Unique ID of your online shop
     * @param string $javascriptScriptVersion Version number of JavaScript, enable iFrame support by passing 'pci3'
     * @param string $userAgent API Client Identifier
     */
    function __construct(array $options)
    {
        foreach ($options as $option => $value) {
            $property = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $option))));

            if (!property_exists($this, $property)) {
                throw new \InvalidArgumentException(sprintf('Option %s is not defined', $option));
            }

            $this->{$property} = $value;
        }
    }

    function getCustomerId()
    {
        return $this->customerId;
    }

    function getSecret()
    {
        return $this->secret;
    }

    function getLanguage()
    {
        return $this->language;
    }

    function getShopId()
    {
        return $this->shopId;
    }

    function getJavascriptScriptVersion()
    {
        return $this->javascriptScriptVersion;
    }

    function getUserAgent()
    {
        return $this->userAgent;
    }

    function serialize()
    {
        return serialize(get_object_vars($this));
    }

    function unserialize($data)
    {
        foreach (unserialize($data) as $property => $value) {
            $this->{$property} = $value;
        }
    }
}
