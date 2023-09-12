<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Ui\Form;

use Creatuity\AIContentMassAction\Model\GetNextAiContentEntry;
use Creatuity\AIContentMassAction\Model\ResourceModel\AiContentQueueEntry\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class AiContentEntryQueueDataProvider extends AbstractDataProvider
{

    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        private readonly GetNextAiContentEntry $getNextAiContentEntry,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData(): array
    {
        return [
            '' => [
                'entry_id' => $this->getNextAiContentEntry->execute()->getEntryId(),
                'product_id' => $this->getNextAiContentEntry->execute()->getProductId(),
            ]
        ];
    }
}