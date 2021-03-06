<?php

namespace Flow;

use Flow\Service\OrderService;
use PHPUnit\Framework\TestCase;

class OrderServiceTest extends FlowClientTestCase
{
    public function testShouldGetAllOrders(): void
    {
        $this->addResponse(200, [
            "id" => 28,
            "name" => "Test product",
        ]);
        [$data, $response] = $this->client->orders->all();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsObject($data);
        $this->assertObjectHasAttribute("results", $data);
        $this->assertObjectHasAttribute("count", $data);
        $this->assertObjectHasAttribute("next", $data);
    }

    public function testShouldCreateAndStartOrder(): void
    {
        $this->addResponse(204, [
            "id" => 1,
            "status" => 1,
            "customer_group" => 1,
        ]);

        [$data,] = $this->client->orders->create(["customer_group" => 1]);
        $this->client->orders->startProduction($data->id);
        $this->assertIsObject($data);
        $this->assertObjectHasAttribute("id", $data);
        $this->assertObjectHasAttribute("customer_group", $data);
        $this->assertEquals(1, $data->customer_group);

    }

    public function testShouldCreateAndDeleteOrder(): void
    {
        $this->addResponse(204, [
            "id" => 1,
            "status" => 1,
            "customer_group" => 1,
        ]);

        [$data,] = $this->client->orders->create(["customer_group" => 1]);
        $this->assertIsObject($data);
        $this->assertObjectHasAttribute("id", $data);
        $this->assertObjectHasAttribute("customer_group", $data);
        $this->assertEquals(1, $data->customer_group);

        [$ol, $response] = $this->client->lines->all([
            "order" => $data->id,
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsObject($ol);
        $this->assertObjectHasAttribute("results", $ol);
        $this->assertObjectHasAttribute("count", $ol);
        $this->assertObjectHasAttribute("next", $ol);

        [,$response] = $this->client->orders->delete($data->id);
        $this->assertEquals(204, $response->getStatusCode());

    }
}
