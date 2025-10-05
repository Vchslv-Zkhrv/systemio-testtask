<?php

namespace App\Enum;

enum PurchaseStatusType: string
{
    /**
     * Purchased successfully
     */
    case SUCCESS = 'success';

    /**
     * Purchase was rejected by payment system
     */
    case REJECT = 'reject';

    /**
     * Something went wrong unexpectedly during the purchase process
     */
    case ERROR = 'error';
}
