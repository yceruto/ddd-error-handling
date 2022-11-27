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

use App\Shared\Domain\Exception\NotFound;

class OrderNotFound extends NotFound
{
    public static function create(string $id): self
    {
        return new self(sprintf('The order "%s" could not be found.', $id));
    }
}
