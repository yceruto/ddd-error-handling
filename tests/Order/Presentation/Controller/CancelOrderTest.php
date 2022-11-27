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

namespace App\Tests\Order\Presentation\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CancelOrderTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    public function testOrderNotFoundJson(): void
    {
        $this->client->jsonRequest('POST', '/order/123-not-found/cancel');

        self::assertResponseStatusCodeSame(404);
        self::assertResponseFormatSame('json');
        self::assertSame(
            '{"title":"Not Found","status":404,"detail":"The order \"123-not-found\" could not be found."}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testOrderNotFoundXml(): void
    {
        $this->client->request('POST', '/order/123-not-found/cancel', [], [], [
            'CONTENT_TYPE' => 'application/xml',
            'HTTP_ACCEPT' => 'application/xml',
        ]);

        self::assertResponseStatusCodeSame(404);
        self::assertResponseFormatSame('xml');
        self::assertSame(
            '<?xml version="1.0"?>
<response><title>Not Found</title><status>404</status><detail>The order "123-not-found" could not be found.</detail></response>',
            trim($this->client->getResponse()->getContent())
        );
    }

    public function testOrderAlreadyShipped(): void
    {
        $this->client->jsonRequest('POST', '/order/123-already-shipped/cancel');

        self::assertResponseStatusCodeSame(422);
        self::assertResponseFormatSame('json');
        self::assertSame(
            '{"title":"Unprocessable Content","status":422,"detail":"The order \"123-already-shipped\" is already shipped."}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testCustomerMismatch(): void
    {
        $this->client->jsonRequest('POST', '/order/123-customer-mismatch/cancel');

        self::assertResponseStatusCodeSame(403);
        self::assertResponseFormatSame('json');
        self::assertSame(
            '{"title":"Forbidden","status":403,"detail":"The order \"123-customer-mismatch\" is not created by the customer \"john23\"."}',
            $this->client->getResponse()->getContent()
        );
    }

    public function testOrderCancelled(): void
    {
        $this->client->jsonRequest('POST', '/order/123/cancel');

        self::assertResponseStatusCodeSame(200);
        self::assertResponseFormatSame('json');
        self::assertSame(
            '{"message":"Order cancelled."}',
            $this->client->getResponse()->getContent()
        );
    }
}
