<?php

namespace Hochstrasser\Wirecard\Request\Seamless\Frontend;

use Hochstrasser\Wirecard\Request\AbstractWirecardRequest;

class InitDataStorageRequest extends AbstractWirecardRequest
{
    protected $requiredParameters = ['language', 'orderIdent', 'returnUrl'];
    protected $fingerprintOrder = ['orderIdent', 'returnUrl', 'language', 'javascriptScriptVersion'];
    protected $endpoint = 'https://checkout.wirecard.com/seamless/dataStorage/init';
    protected $resultClass = 'Hochstrasser\Wirecard\Model\Seamless\Frontend\DataStorageInitResult';

    static function withOrderIdentAndReturnUrl($orderIdent, $returnUrl)
    {
        return (new static())
            ->setOrderIdent($orderIdent)
            ->setReturnUrl($returnUrl)
            ;
    }

    /**
     * Unique reference to the order of your consumer
     *
     * @param string $orderIdent
     * @return InitDataStorageRequest
     */
    function setOrderIdent($orderIdent)
    {
        return $this->addParam('orderIdent', $orderIdent);
    }

    /**
     * Return URL for outdated browsers
     *
     * @param string $returnUrl
     * @return InitDataStorageRequest
     */
    function setReturnUrl($returnUrl)
    {
        return $this->addParam('returnUrl', $returnUrl);
    }

    protected function getRawParameters()
    {
        $params = parent::getRawParameters();

        if (empty($params['language'])) {
            $params['language'] = $this->getContext()->getLanguage();
        }

        if (empty($params['javascriptScriptVersion'])) {
            $params['javascriptScriptVersion'] = $this->getContext()->getJavascriptScriptVersion();
        }

        return $params;
    }
}
