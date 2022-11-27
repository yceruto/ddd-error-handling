<?php

declare(strict_types=1);

/*
 * This file is part of the Second package.
 *
 * © Second <contact@scnd.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Order\Presentation\Controller;

use App\Order\Domain\Model\CustomerMismatch;
use App\Order\Domain\Model\OrderAlreadyShipped;
use App\Order\Domain\Model\OrderNotFound;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/order/{id}/cancel", methods: "POST")]
class CancelOrder
{
    public function __invoke(string $id): JsonResponse
    {
        $data = match ($id) {
            '123-not-found' => throw OrderNotFound::create($id),
            '123-already-shipped' => throw OrderAlreadyShipped::create($id),
            '123-customer-mismatch' => throw CustomerMismatch::create($id, 'john23'),
            default => ['message' => 'Order cancelled.']
        };

        return new JsonResponse($data);
    }
}
