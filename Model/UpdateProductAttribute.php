<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model;

use Magento\Catalog\Model\Product\Action;
use Magento\Framework\Exception\LocalizedException;

class UpdateProductAttribute
{
    public function __construct(
        private readonly Action $action,
        private readonly array $supportedAttributes = []
    ) {
    }

    /**
     * @throws LocalizedException
     */
    public function execute(int $productId, string $attrCode, mixed $value): void
    {
        if (!isset($this->supportedAttributes[$attrCode])) {
            throw new LocalizedException(__('Cannot update attribute %1', $attrCode));
        }

        $this->action->updateAttributes([$productId], [$attrCode => $value], 0);
    }
}