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

namespace App\Order\Domain\Model;

use App\Shared\Domain\Exception\AccessDenied;

class CustomerMismatch extends AccessDenied
{
    public static function create(string $orderId, string $customerId): self
    {
        return new self(sprintf('The order "%s" is not created by the customer "%s".', $orderId, $customerId));
    }
}
