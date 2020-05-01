<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Checker;

use Sylius\Component\Resource\Model\CodeAwareInterface;

const PRODUCT_CODE_PREFIX = 'HIBOUTIK';

class HiboutikProductChecker
{
    public function isHiboutikProduct(CodeAwareInterface $codeAware): bool
    {
        return substr($codeAware->getCode(), 0, strlen(PRODUCT_CODE_PREFIX)) == PRODUCT_CODE_PREFIX;
    }
}
