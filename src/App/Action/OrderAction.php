<?php
namespace App\Action;

use \Common\Base\DbRepository;


class OrderAction
{
    public function __construct(
        protected $faker = null,
    ) {
        $this->faker = \Faker\Factory::create();
    }

    public function calc(array $customerAddressDTO): array
    {
        return [
            'order' => [
                'price' => $this->faker->randomNumber(2),
            ],
        ];
    }
    
    public function create(array $orderDTO): array
    {
        $products = array_get($orderDTO, 'order.products');
        \ORM::get_db()->beginTransaction();

        $order = DbRepository::getRepo('order')->create([]);
        $orderId = $order->offsetGet('id');
        
        $orderItemRepo = DbRepository::getRepo('order_item');
        foreach ($products as $item) {

            $orderItemRepo->create([
                'order_id' => $orderId,
                'product_id' => $item['id'],
                'count' => $item['count'],
            ]);
        }

        \ORM::get_db()->commit();
        return [
            'order' => [
                'id' => $orderId,
            ],
        ];
    }
    
    public function list(array $filter = []): array
    {
        $list = DbRepository::getRepo('order')->findAll();
        return [
            'orders' => $list->toArray(),
        ];
    }
    
    public function view(array $filter): array
    {
        $getResult = function ($order = null) {

            return [
                'order' => $order,
            ];
        };

        $orderModel = DbRepository::getRepo('order')->find($filter['order_id']);
        $order = $orderModel->toArray();
        if (empty($order)) {

            return $getResult(null);
        }
        
        $orderItemsCollection = DbRepository::getRepo('order_item')->findWhere([
            'order_id' => $order['id'],
        ]);
        $orderItems = $orderItemsCollection->toArray();
        array_set($order, 'items', $orderItems);

        return $getResult($order);
    }
}
