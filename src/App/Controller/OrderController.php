<?php
namespace App\Controller;

use Rakit\Validation\Validator;

use Common\Helper\ControllerHelper;

use App\Action\OrderAction;


class OrderController
{
    protected Validator|null $validator = null;

    public function __construct(
        protected OrderAction $action
    ) {
        $this->validator = new Validator;
    }
    
    public function calc(array $params): array
    {
        $validation = $this->validator->validate($params, [
            'customer_address' => 'required|array',
            'customer_address.street' => 'required',
            'customer_address.house_number' => 'required|numeric',
            'customer_address.flat_number' => 'required|numeric',
            'customer_address.entrance' => 'numeric',
            'stomer_address.floor' => 'numeric',
        ]);
        if ($validation->fails()) {

            return ControllerHelper::getErrorResponse($validation->errors()->firstOfAll(), 400);
        }

        return ControllerHelper::getResponse(
            function () use ($params) {

                return $this->action->calc($params);
            },
        );
    }
    
    public function create(array $params): array
    {
        $validation = $this->validator->validate($params, [
            'order' => 'required|array',
            'order.products' => 'required|array',
            'order.products.*.id' => 'required|numeric',
            'order.products.*.count' => 'required|numeric',
        ]);
        if ($validation->fails()) {

            return ControllerHelper::getErrorResponse($validation->errors()->firstOfAll(), 400);
        }

        return ControllerHelper::getResponse(
            function () use ($params) {

                return $this->action->create($params);
            },
            201,
        );
    }
    
    public function list(array $params): array
    {
        return ControllerHelper::getResponse(
            function () use ($params) {

                return $this->action->list($params);
            },
        );
    }

    public function view(array $params): array
    {
        $validation = $this->validator->validate($params, [
            'order_id' => 'required|numeric',
        ]);
        if ($validation->fails()) {

            return ControllerHelper::getErrorResponse($validation->errors()->firstOfAll(), 400);
        }

        return ControllerHelper::getResponse(
            function () use ($params) {

                return $this->action->view($params);
            },
        );
    }
}
