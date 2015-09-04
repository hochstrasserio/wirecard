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
    private $backendPassword;

    /**
     * Constructor
     *
     * @param array $options
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

    function getBackendPassword()
    {
        return $this->backendPassword;
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
