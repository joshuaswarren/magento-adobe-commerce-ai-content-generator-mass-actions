<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model\ResourceModel;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class AiContentQueueEntry extends AbstractDb
{
    public const TABLE_NAME = 'creatuity_ai_content_queue';

    protected function _construct(): void
    {
        $this->_init(self::TABLE_NAME, AiContentQueueEntryInterface::ID);
    }
}