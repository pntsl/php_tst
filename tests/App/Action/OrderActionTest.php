<?php

use PHPUnit\Framework\TestCase;

use App\Action\OrderAction;

final class OrderActionTest extends TestCase
{
    public function testCalc(): void
    {
        $action = new OrderAction();
        $result = $action->calc([]);
        $this->assertArrayHasKey('order', $result);

        $order = $result['order'];
        $this->assertArrayHasKey('price', $order);
        
        $price = $order['price'];
        $this->assertIsNumeric($price);
        $this->assertThat($price, $this->greaterThan(0));
    }

    public function testCreate(): void
    {
        $action = new OrderAction();
        $result = $action->create([
            'order' => [
                'products' => [
                    [
                        'id' => 13,
                        'count' => 17,
                    ]
                ]
            ]
        ]);

        $this->assertArrayHasKey('order', $result);

        $order = $result['order'];
        $this->assertArrayHasKey('id', $order);

        $orderId = $order['id'];
        $this->assertIsNumeric($orderId);
        $this->assertThat($orderId, $this->greaterThan(0));

        $orderModel = \Common\Base\DbRepository::getRepo('order')->find($orderId);
        $order = $orderModel->toArray();
        $this->assertArrayHasKey('id', $order);
        $this->assertEquals($orderId, $order['id']);
    }
    
    public function testList(): void
    {
        $action = new OrderAction();
        $result = $action->list([]);

        $this->assertArrayHasKey('orders', $result);
        $orders = $result['orders'];
        $this->assertNotEmpty($orders);

        $order = array_first($orders);
        $this->assertArrayHasKey('id', $order);
        $orderId = $order['id'];
        $this->assertIsNumeric($orderId);
        $this->assertThat($orderId, $this->greaterThan(0));
    }
    
    public function testView(): void
    {
        $action = new OrderAction();
        $result = $action->list([]);

        $orders = $result['orders'];
        $order = array_first($orders);
        $orderId = $order['id'];

        $result = $action->view([
            'order_id' => $orderId,
        ]);
        $this->assertArrayHasKey('order', $result);

        $order = $result['order'];
        $this->assertArrayHasKey('id', $order);
        $orderId = $order['id'];
        $this->assertIsNumeric($orderId);
        $this->assertThat($orderId, $this->greaterThan(0));
        $this->assertArrayHasKey('items', $order);

        $items = $order['items'];
        $this->assertNotEmpty($items);

        $item = array_first($items);
        $this->assertArrayHasKey('id', $item);
        $this->assertArrayHasKey('product_id', $item);
        $this->assertArrayHasKey('count', $item);
    }
}
