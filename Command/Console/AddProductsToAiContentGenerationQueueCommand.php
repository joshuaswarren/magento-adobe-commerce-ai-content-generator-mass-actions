<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Command\Console;

use Creatuity\AIContentMassAction\Api\AiContentQueueEntryRepositoryInterface;
use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Creatuity\AIContentMassAction\Model\AiContentQueueEntryHydrator;
use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AddProductsToAiContentGenerationQueueCommand extends Command
{
    public function __construct(
        private readonly AiContentQueueEntryRepositoryInterface $aiContentQueueEntryRepository,
        private readonly AiContentQueueEntryHydrator $aiContentQueueEntryHydrator
    )
    {
        parent::__construct('creatuity:products:ai-content-generate');
    }

    protected function configure(): void
    {
        $this->addOption('content_type', 'c', InputOption::VALUE_REQUIRED);
        $this->addArgument('product_ids', InputArgument::IS_ARRAY | InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $contentType = $input->getOption('content_type');
        $productIds = $input->getArgument('product_ids');

        foreach ($productIds as $productId) {
            $this->aiContentQueueEntryRepository->save($this->aiContentQueueEntryHydrator->hydrate([
                AiContentQueueEntryInterface::CONTENT_TYPE => $contentType,
                AiContentQueueEntryInterface::PRODUCT_ID => $productId
            ]));
        }

        return Cli::RETURN_SUCCESS;
    }
}