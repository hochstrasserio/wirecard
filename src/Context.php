<?php

namespace Hochstrasser\Wirecard;

use Hochstrasser\Wirecard\MessageFactory;
use Hochstrasser\Wirecard\GuzzlePsrMessageFactory;

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
    private $messageFactory;

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

        if (null === $this->messageFactory) {
            $this->messageFactory = new GuzzlePsrMessageFactory();
        }
    }

    /**
     * @return string
     */
    function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @return string
     */
    function getSecret()
    {
        return $this->secret;
    }

    /**
     * @return string
     */
    function getLanguage()
    {
        return $this->language;
    }

    /**
     * @return string
     */
    function getShopId()
    {
        return $this->shopId;
    }

    /**
     * @return string
     */
    function getJavascriptScriptVersion()
    {
        return $this->javascriptScriptVersion;
    }

    /**
     * @return string
     */
    function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @return string
     */
    function getBackendPassword()
    {
        return $this->backendPassword;
    }

    /**
     * @return MessageFactory
     */
    function getMessageFactory()
    {
        return $this->messageFactory;
    }

    /**
     * @return string
     */
    function serialize()
    {
        return serialize(get_object_vars($this));
    }

    /**
     * @return Context
     */
    function unserialize($data)
    {
        foreach (unserialize($data) as $property => $value) {
            $this->{$property} = $value;
        }
    }
}
