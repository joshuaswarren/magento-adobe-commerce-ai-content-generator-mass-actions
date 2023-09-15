<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model\ResourceModel\AiContentQueueEntry;

use Creatuity\AIContentMassAction\Model\ResourceModel\AiContentQueueEntry as AiContentQueueEntryResource;
use Creatuity\AIContentMassAction\Model\AiContentQueueEntry;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * @method \Creatuity\AIContentMassAction\Model\AiContentQueueEntry[] getItems()
 * @method setItems(\Creatuity\AIContentMassAction\Model\AiContentQueueEntry[] $items)
 */
class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(AiContentQueueEntry::class, AiContentQueueEntryResource::class);
    }
}