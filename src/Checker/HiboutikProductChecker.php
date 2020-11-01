<?php

declare(strict_types=1);

namespace QNeyrat\SyliusHiboutikPlugin\Checker;

use Sylius\Component\Resource\Model\CodeAwareInterface;

class HiboutikProductChecker
{
    /**
     * @var string
     */
    private $productCodePrefix;

    /**
     * HiboutikProductChecker constructor.
     * @param string $productCodePrefix
     */
    public function __construct(string $productCodePrefix)
    {
        $this->productCodePrefix = $productCodePrefix;
    }

    public function isHiboutikProduct(CodeAwareInterface $codeAware): bool
    {
        return substr($codeAware->getCode(), 0, strlen($this->productCodePrefix)) == $this->productCodePrefix;
    }
}
