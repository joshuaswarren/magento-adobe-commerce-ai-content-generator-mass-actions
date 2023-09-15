<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Magento\Framework\Model\AbstractModel;

class AiContentQueueEntry extends AbstractModel implements AiContentQueueEntryInterface
{
    protected function _construct(): void
    {
        $this->_init(ResourceModel\AiContentQueueEntry::class);
    }

    public function getEntryId(): int
    {
        return (int) $this->getData(self::ID);
    }

    public function setEntryId(int $id): void
    {
        $this->setData(self::ID, $id);
    }

    public function getContentType(): string
    {
        return (string) $this->getData(self::CONTENT_TYPE);
    }

    public function setContentType(string $type): void
    {
        $this->setData(self::CONTENT_TYPE, $type);
    }

    public function getCreatedAt(): string
    {
        return (string) $this->getData(self::CREATED_AT);
    }

    public function setCreatedAt(string $dateTime): void
    {
        $this->setData(self::CREATED_AT, $dateTime);
    }

    public function getProductId(): int
    {
        return (int) $this->getData(self::PRODUCT_ID);
    }

    public function setProductId(int $productId): void
    {
        $this->setData(self::PRODUCT_ID, $productId);
    }
}