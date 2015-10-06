<?php

namespace Hochstrasser\Wirecard\Model\Common;

/**
 * Fund Transfer Type
 *
 * @see https://integration.wirecard.at/doku.php/back-end_operations:functional_wcp_wcs:transaction-based_operations:transferfund#skrillwallet
 * @author Christoph Hochstrasser <christoph@hochstrasser.io>
 */
abstract class FundTransferType
{
    const EXISTINGORDER = 'EXISTINGORDER';
    const MONETA = 'MONETA';
    const SEPA_CT = 'SEPA-CT';
    const SKRILLWALLET = 'SKRILLWALLET';
}
