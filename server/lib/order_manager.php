<?php
class OrderManager
{
    private $filename;
    public function __construct($filename)
    {
        $this->filename = $filename;
    }
    public function writeOrderData($jsonData, $filename)
    {
        require_once 'order.php';
        $order = Order::fromJson($jsonData);
        $orderObject = new Order($order->id, $order->name, $order->phoneNumber, $order->address, $order->cardNumber, $order->cardCVV, $order->items);
        file_put_contents($filename, $orderObject->toJson() . "\n", FILE_APPEND);
        return [
            'success' => true,
            'message' => 'Order with name ' . $orderObject->name . ' written successfully.',
            'order' => $orderObject->toJson()
        ];
    }
}