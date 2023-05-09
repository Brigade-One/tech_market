<?php

class OrderController
{
    public static function order($filename, $token)
    {
        require_once 'lib/order.php';
        require_once 'lib/order_manager.php';
        $order = new OrderManager($filename);
        $jsonData = file_get_contents('php://input');
        $result = $order->writeOrderData($jsonData, $filename);
        OrderController::_sendOrderResponse($result);
    }

    public static function getOrderHistory($filename, $token)
    {
        require_once 'lib/order_manager.php';

        $order = new OrderManager($filename);
        $result = $order->getUserOrderHistory($filename, $token);
        OrderController::_sendOrderResponse($result);
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