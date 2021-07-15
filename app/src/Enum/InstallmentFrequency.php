<?php

declare(strict_types=1);

namespace App\Enum;

class InstallmentFrequency extends AbstractEnum
{
    use EnumMapTrait;
    
    public const INSTALLMENT_FREQUENCY_DAILY = 'daily';
    public const INSTALLMENT_FREQUENCY_WEEKLY = 'weekly';
    public const INSTALLMENT_FREQUENCY_BIWEEKLY = 'biweekly';
    public const INSTALLMENT_FREQUENCY_MONTHLY = 'monthly';
    public const INSTALLMENT_FREQUENCY_QUARTERLY = 'quarterly';
    public const INSTALLMENT_FREQUENCY_ANNUAL = 'annual';
}
