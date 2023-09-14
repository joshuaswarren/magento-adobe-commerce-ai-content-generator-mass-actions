<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Ui\Listing\Column;

use Creatuity\AIContent\Enum\AiContentTypeEnum;
use Magento\Ui\Component\Listing\Columns\Column;

class ContentTypeColumn extends Column
{
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                $item[$name] = match ($item[$name]) {
                    AiContentTypeEnum::DESCRIPTION_GROUP->value => __('Descriptions'),
                    AiContentTypeEnum::META_GROUP->value => __('Meta Tags'),
                    default => __('-')
                };
            }
        }
        return $dataSource;
    }
}