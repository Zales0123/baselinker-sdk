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

use Spinbits\BaselinkerSdk\Model\OrderUpdateModel;
use App\Integration\BaseLinker\Service\OrderUpdateService;
use Spinbits\BaselinkerSdk\Handler\HandlerInterface;
use Spinbits\BaselinkerSdk\Rest\Exception\InvalidArgumentException;
use Spinbits\BaselinkerSdk\Rest\Input;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolation;

class OrderUpdateAction implements HandlerInterface
{
    private ValidatorInterface $validator;
    private OrderUpdateService $orderUpdateService;

    public function __construct(ValidatorInterface $validator, OrderUpdateService $orderUpdateService)
    {
        $this->validator = $validator;
        $this->orderUpdateService = $orderUpdateService;
    }

    /**
     * @param Input $input
     * @return array
     * @throws InvalidArgumentException
     */
    public function handle(Input $input): array
    {
        $input = new OrderUpdateModel($input->body->all());
        $result = $this->validator->validate($input);
        $this->assertIsValid($result);

        $orders = $this->orderUpdateService->updateOrders($input);

        return ['counter' => count($orders)];
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
