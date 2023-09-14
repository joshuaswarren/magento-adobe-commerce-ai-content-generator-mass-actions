<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Ui\Listing\Column;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class ProductNameColumn extends Column
{
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        private readonly UrlInterface $url,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                $item[$name] = $this->getFieldContent($item);
            }
        }
        return $dataSource;
    }

    private function getFieldContent(array $item)
    {
        $productId = $item[AiContentQueueEntryInterface::PRODUCT_ID];
        $url = $this->url->getUrl('catalog/product/edit', ['id' => $productId]);

        return '<a href="' . $url . '" target="_blank">' . $item['product_name'] . '</a>';
    }
}