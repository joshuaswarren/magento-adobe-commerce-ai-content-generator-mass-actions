<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model\Validator;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;

class AiContentQueueEntryProductIdValidator implements AiContentQueueEntryValidatorInterface
{

    public function __construct(
        private readonly CollectionFactory $productCollectionFactory,
        private readonly ValidationResultFactory $validationResultFactory
    ) {
    }

    public function validate(AiContentQueueEntryInterface $aiContentQueueEntry): ValidationResult
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addFieldToFilter('entity_id', ['eq' => $aiContentQueueEntry->getProductId()]);
        $errors = [];
        if (!$collection->getSize()) {
            $errors[] = __('Product ID %1 doesn\'t exist', $aiContentQueueEntry->getProductId());
        }

        return $this->validationResultFactory->create(['errors' => $errors]);
    }
}