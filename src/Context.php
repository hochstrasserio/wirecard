<?php

namespace Hochstrasser\Wirecard;

class Context
{
    const PCI3 = 'pci3';

    private $customerId;
    private $secret;
    private $language;
    private $shopId;
    private $javascriptScriptVersion;
    private $userAgent;

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
    function __construct(
        $customerId,
        $secret,
        $language,
        $shopId = null,
        $javascriptScriptVersion = null,
        $userAgent = 'Hochstrasser/Wirecard'
    )
    {
        $this->customerId = $customerId;
        $this->secret = $secret;
        $this->language = $language;
        $this->shopId = $shopId;
        $this->javascriptScriptVersion = $javascriptScriptVersion;
        $this->userAgent = $userAgent;
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
}
