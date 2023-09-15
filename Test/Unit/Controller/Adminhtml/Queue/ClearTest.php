<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Test\Unit\Controller\Adminhtml\Queue;

use Creatuity\AIContentMassAction\Controller\Adminhtml\Queue\Clear;
use Creatuity\AIContentMassAction\Api\AiContentQueueEntryRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ClearTest extends TestCase
{
    private readonly Clear $clearController;
    private readonly AiContentQueueEntryRepositoryInterface|MockObject $aiContentQueueEntryRepository;
    private readonly Redirect|MockObject $redirectResult;
    private readonly ManagerInterface|MockObject $manager;
    private readonly LoggerInterface|MockObject $logger;

    protected function setUp(): void
    {
        $redirectFactory = $this->createMock(RedirectFactory::class);
        $this->aiContentQueueEntryRepository = $this->createMock(AiContentQueueEntryRepositoryInterface::class);
        $this->redirectResult = $this->createMock(Redirect::class);
        $this->manager = $this->createMock(ManagerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $redirectFactory->expects($this->once())->method('create')->willReturn($this->redirectResult);

        $this->clearController = new Clear(
            $this->createMock(Context::class),
            $this->aiContentQueueEntryRepository,
            $redirectFactory,
            $this->logger,
            $this->manager
        );
    }

    public function testExecuteSuccess(): void
    {
        $this->aiContentQueueEntryRepository->expects($this->once())->method('clear');
        $this->manager->expects($this->once())->method('addSuccessMessage')->with(__('Queue has been cleared'));

        $result = $this->clearController->execute();
        $this->assertSame($this->redirectResult, $result);
    }

    public function testExecuteFailure(): void
    {
        $exceptionMessage = 'Error occurred while clearing the queue';
        $this->aiContentQueueEntryRepository->expects($this->once())
            ->method('clear')
            ->willThrowException(new \RuntimeException());
        $this->manager->expects($this->once())->method('addErrorMessage')->with($exceptionMessage);
        $this->logger->expects($this->once())->method('error')->with($exceptionMessage);

        $result = $this->clearController->execute();
        $this->assertSame($this->redirectResult, $result);
    }
}

