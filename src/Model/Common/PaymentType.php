<?php

namespace Hochstrasser\Wirecard\Model\Common;

/**
 * The parameter paymentType contains the value of the payment method the user
 * selected in your online shop.
 *
 * Please be aware that you can only use those payment methods you have purchased
 * and enabled by Wirecard.
 *
 * @author Christoph Hochstrasser <christoph@hochstrasser.io>
 */
abstract class PaymentType
{
    /**
     * @var array
     */
    private static $constants;

    /**
     * The consumer may select one of the activated payment methods directly in
     * Wirecard Checkout Page. Please note that SELECT is only available for
     * Wirecard Checkout Page.
     */
    const Select = 'SELECT';
    const BancontactMisterCash = 'BANCONTACT_MISTERCASH';
    const CreditCard = 'CCARD';
    const CreditCardMailOrderAndTelephoneOrder = 'CCARD-MOTO';
    const eKonto = 'EKONTO';
    const ePayBg = 'EPAY_BG';
    const EPS = 'EPS';
    const giropay = 'GIROPAY';
    const iDEAL = 'IDL';
    const Installment = 'INSTALLMENT';
    const Invoice = 'INVOICE';
    const monetaRu = 'MONETA';
    const mpass = 'MPASS';
    const Przelewy24 = 'PRZELEWY24';
    const PayPal = 'PAYPAL';
    const paybox = 'PBX';
    const POLi = 'POLI';
    const paysafecard = 'PSC';
    const Quick = 'QUICK';
    const SEPADirectDebit = 'SEPA-DD';
    const SkrillDirect = 'SKRILLDIRECT';
    const SkrillDigitalWallet = 'SKRILLWALLET';
    const SOFORTBanking = 'SOFORTUEBERWEISUNG';
    const TatraPay = 'TATRAPAY';
    const Trustly = 'TRUSTLY';
    const TrustPay = 'TRUSTPAY';
    const MyVoucher = 'VOUCHER';

    /**
     * @param string $paymentType
     * @return bool
     */
    static function isValid($paymentType)
    {
        return in_array($paymentType, static::getValues(), true);
    }

    /**
     * @return array
     */
    static function getValues()
    {
        if (null === static::$constants) {
            static::$constants = (new \ReflectionClass(get_called_class()))->getConstants();
        }

        return static::$constants;
    }
}
