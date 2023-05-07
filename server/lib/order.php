<?php
class Order
{
    public $id;
    public $name;
    public $phoneNumber;
    public $address;
    public $cardNumber;
    public $cardCVV;
    public $items;

    public function __construct($id, $name, $phoneNumber, $address, $cardNumber, $cardCVV, $items)
    {
        $this->id = $id;
        $this->name = $name;
        $this->phoneNumber = $phoneNumber;
        $this->address = $address;
        $this->cardNumber = $cardNumber;
        $this->cardCVV = $cardCVV;
        $this->items = $items;
    }

    static function fromJson($json)
    {
        $data = json_decode($json, true);

        $id = isset($data['id']) ? $data['id'] : null;
        $name = isset($data['name']) ? $data['name'] : null;
        $phoneNumber = isset($data['phoneNumber']) ? $data['phoneNumber'] : null;
        $address = isset($data['address']) ? $data['address'] : null;
        $cardNumber = isset($data['cardNumber']) ? $data['cardNumber'] : null;
        $cardCVV = isset($data['cardCVV']) ? $data['cardCVV'] : null;
        $items = isset($data['items']) ? $data['items'] : null;

        return new Order($id, $name, $phoneNumber, $address, $cardNumber, $cardCVV, $items);
    }

    function toJson()
    {
        return json_encode([
            'id' => $this->id,
            'name' => $this->name,
            'phoneNumber' => $this->phoneNumber,
            'address' => $this->address,
            'cardNumber' => $this->cardNumber,
            'cardCVV' => $this->cardCVV,
            'items' => $this->items
        ]);
    }
}