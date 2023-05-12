<?php

class OrderController
{
    public static function writeOrderData($token, $jsonData)
    {
        require_once 'lib/order.php';
        require_once 'lib/order_manager.php';
        $order = new OrderManager($filename);
        $result = $order->writeOrderData($jsonData);
        OrderController::_sendOrderResponse($result);
        return $result;
    }
    public static function getOrderHistory($token)
    {
        require_once 'lib/order_manager.php';

        $order = new OrderManager($filename);
        $result = $order->getUserOrderHistory($token);
        OrderController::_sendOrderResponse($result);
        return $result;
    }

    private static function _sendOrderResponse($result)
    {
        header('Content-Type: application/json');
        if ($result['success'] === true) {
            echo json_encode(['success' => true, 'message' => $result['message'], 'orders' => $result['orders']]);
        } else {
            echo json_encode(['success' => false, 'message' => $result['message']]);
        }
    }


}