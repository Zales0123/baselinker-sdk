<?php
/**
 * @author Marcin Hubert <>
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Spinbits\BaselinkerSdk\Handler\Sylius;

use Spinbits\BaselinkerSdk\Model\OrderAddModel;
use App\Integration\BaseLinker\Service\OrderCreateService;
use Spinbits\BaselinkerSdk\Handler\HandlerInterface;
use Spinbits\BaselinkerSdk\Rest\Exception\InvalidArgumentException;
use Spinbits\BaselinkerSdk\Rest\Input;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderAddAction implements HandlerInterface
{
    private ValidatorInterface $validator;
    private OrderCreateService $orderCreateService;

    public function __construct(ValidatorInterface $validator, OrderCreateService $orderCreateService)
    {
        $this->validator = $validator;
        $this->orderCreateService = $orderCreateService;
    }

    /**
     * @param Input $input
     * @return array
     * @throws InvalidArgumentException
     */
    public function handle(Input $input): array
    {
        $input = new OrderAddModel($input->body->all());

        $result = $this->validator->validate($input);
        $this->assertIsValid($result);

        $order = $this->orderCreateService->createOrder($input);

        return ['order_id' => $order->getId()];
    }

    /**
     * @param ConstraintViolationListInterface|ConstraintViolation[] $result
     *
     * @throws InvalidArgumentException
     */
    private function assertIsValid(ConstraintViolationListInterface $result): void
    {
        if (count($result) < 1) {
            return;
        }

        $errors = [];
        foreach ($result as $violation) {
            $errors[] = $violation->getPropertyPath() . ": " . $violation->getMessage();
        }

        throw new InvalidArgumentException('validation failed: ' . implode("; ", $errors));
    }
}
