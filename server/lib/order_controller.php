<?php

class OrderController
{
    public static function writeOrderData($filename, $token)
    {
        require_once 'lib/order.php';
        require_once 'lib/order_manager.php';
        $order = new OrderManager($filename);
        $jsonData = file_get_contents('php://input');
        $result = $order->writeOrderData($jsonData, $filename);
        OrderController::_sendOrderResponse($result);
        return $result['order'];
    }
    public static function getOrderHistory($token)
    {
        require_once 'lib/order_manager.php';

        $order = new OrderManager($filename);
        $result = $order->getUserOrderHistory($token);
        OrderController::_sendOrderResponse($result);
        echo "getOrderHistory method called";
        return $result['orders'];
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