<?php

declare(strict_types=1);

namespace Creatuity\AIContentMassAction\Model\Validator;

use Creatuity\AIContentMassAction\Api\Data\AiContentQueueEntryInterface;
use Magento\Framework\Validation\ValidationResult;
use Magento\Framework\Validation\ValidationResultFactory;

class AiContentQueueEntryCompositeEntryValidator implements AiContentQueueEntryValidatorInterface
{

    /**
     * @param ValidationResultFactory $validationResultFactory
     * @param AiContentQueueEntryValidatorInterface[] $validators
     */
    public function __construct(
        private readonly ValidationResultFactory $validationResultFactory,
        private readonly array $validators = []
    ) {
    }

    public function validate(AiContentQueueEntryInterface $aiContentQueueEntry): ValidationResult
    {
        $errors = [];
        foreach ($this->validators as $validator) {
            if (!$validator instanceof AiContentQueueEntryValidatorInterface) {
                throw new \InvalidArgumentException(
                    get_class($validator) . ' does not implement ' . AiContentQueueEntryValidatorInterface::class
                );
            }
            $errors[] = $validator->validate($aiContentQueueEntry)->getErrors();
        }

        return $this->validationResultFactory->create(['errors' => array_merge([], ...$errors)]);
    }
}