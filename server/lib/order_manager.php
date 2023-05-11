<?php
class OrderManager
{
    private $filename;
    public function __construct($filename)
    {
        $this->filename = $filename;
    }
    public function writeOrderData($jsonData)
    {
        require_once 'order.php';
        $order = Order::fromJson($jsonData);
        $filename = './data/orders.txt';
        $orders = $this->readOrderData($filename)['orders'];
        foreach ($orders as $worder) {
            if ($worder->id === $this->generateId($jsonData)) {
                return [
                    'success' => false,
                    'message' => 'The order already written.'
                ];
            }
        }
        $orderObject = new Order($this->generateId($jsonData), $order->email, $order->name, $order->phoneNumber, $order->address, $order->cardNumber, $order->cardCVV, $order->items);
        file_put_contents($filename, $orderObject->toJson() . "\n", FILE_APPEND);
        return [
            'success' => true,
            'message' => 'Order with name ' . $orderObject->name . ' written successfully.',
            'orders' => $orderObject->toJson()
        ];
    }
    // Generate a unique id for each order 
    //(but if the order data is the same, the id will be the same, so we can check if the order is already written or not)
    public function generateId($jsonData)
    {
        $id = hash('sha256', $jsonData);
        return $id;
    }
    public function readOrderData($filename)
    {
        require_once 'order.php';
        $orders = [];

        $file = fopen("./data/orders.txt", "r");

        while (!feof($file)) {
            $line = fgets($file);
            if ($line !== false) {
                $order = Order::fromJson($line);
                array_push($orders, $order);
            }
        }
        fclose($file);
        return [
            'success' => true,
            'message' => 'Order history read successfully.',
            'orders' => $orders
        ];
    }
    public function getUserOrderHistory($token)
    {

        require_once 'token_manager.php';

        $tokenManager = new TokenManager();
        $user = $tokenManager->retrieveUserData($token);
        $orders = $this->readOrderData($filename)['orders'];
        $userOrders = [];
        foreach ($orders as $order) {
            if ($order->email === $user->email) {
                array_push($userOrders, $order);
            }
        }

        return [
            'success' => true,
            'message' => 'User order history read successfully.',
            'orders' => $userOrders
        ];
    }
}