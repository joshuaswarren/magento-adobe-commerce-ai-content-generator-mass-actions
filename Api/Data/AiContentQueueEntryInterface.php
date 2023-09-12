<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Api\Data;

interface AiContentQueueEntryInterface
{
    public const ID = 'entry_id';
    public const PRODUCT_ID = 'product_id';
    public const CONTENT_TYPE = 'content_type';
    public const CREATED_AT = 'created_at';

    /**
     * @return int|null
     */
    public function getId();

    /**
     * @param int|null $id
     * @return mixed
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getEntryId(): int;

    /**
     * @param int $id
     * @return void
     */
    public function setEntryId(int $id): void;

    /**
     * @return string
     */
    public function getContentType(): string;

    /**
     * @return int
     */
    public function getProductId(): int;

    /**
     * @param int $productId
     * @return void
     */
    public function setProductId(int $productId): void;

    /**
     * @param string $type
     * @return void
     */

    public function setContentType(string $type): void;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    /**
     * @param string $dateTime
     * @return void
     */
    public function setCreatedAt(string $dateTime): void;
}