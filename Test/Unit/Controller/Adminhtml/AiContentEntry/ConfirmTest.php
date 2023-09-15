<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Controller\Adminhtml\AiContentEntry;

use Creatuity\AIContentMassAction\Api\AiContentQueueEntryRepositoryInterface;
use Creatuity\AIContentMassAction\Controller\Adminhtml\AiContentEntry\Confirm;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ConfirmTest extends TestCase
{
    private readonly AiContentQueueEntryRepositoryInterface|MockObject $aiContentQueueEntryRepository;
    private readonly RequestInterface|MockObject $request;
    private readonly JsonFactory|MockObject $jsonFactory;
    private readonly LoggerInterface|MockObject $logger;
    private readonly Confirm $confirm;

    protected function setUp(): void
    {
        $this->aiContentQueueEntryRepository = $this->createMock(AiContentQueueEntryRepositoryInterface::class);
        $this->request = $this->createMock(RequestInterface::class);
        $this->jsonFactory = $this->createMock(JsonFactory::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->context = $this->createMock(Context::class);

        $this->confirm = new Confirm(
            $this->context,
            $this->aiContentQueueEntryRepository,
            $this->request,
            $this->jsonFactory,
            $this->logger
        );
    }

    public function testExecuteSuccess(): void
    {
        $this->request->expects($this->any())->method('getParam')
            ->with('entry_id')
            ->willReturn('123');

        $this->jsonFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->createJsonResponse(['success' => true]));

        $response = $this->confirm->execute();
        $this->assertInstanceOf(Json::class, $response);
    }

    public function testExecuteCouldNotDeleteException(): void
    {
        $this->request->expects($this->any())->method('getParam')
            ->with('entry_id')
            ->willReturn('123');

        $this->aiContentQueueEntryRepository->expects($this->once())->method('deleteById')
            ->willThrowException(new CouldNotDeleteException(__('Delete Error')));

        $this->jsonFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->createJsonResponse(['success' => false, 'message' => 'Delete Error']));

        $this->logger->expects($this->once())
            ->method('error');

        $response = $this->confirm->execute();
        $this->assertInstanceOf(Json::class, $response);
    }

    public function testExecuteUnknownException(): void
    {
        $this->request->expects($this->any())->method('getParam')
            ->with('entry_id')
            ->willReturn('123');

        $this->aiContentQueueEntryRepository->expects($this->once())->method('deleteById')
            ->willThrowException(new \Exception('Unknown Error'));

        $this->jsonFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->createJsonResponse(['success' => false, 'message' => 'Unknown error occurred']));

        $this->logger->expects($this->once())
            ->method('error');

        $response = $this->confirm->execute();

        $this->assertInstanceOf(Json::class, $response);
    }

    private function createJsonResponse(array $data): Json|MockObject
    {
        $jsonMock = $this->createMock(Json::class);
        $jsonMock->expects($this->once())->method('setData')->with($data)->willReturnSelf();

        return $jsonMock;
    }
}
