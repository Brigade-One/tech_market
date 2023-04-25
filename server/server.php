<?php

$responseData = [
    'name' => 'John Doe',
    'email' => 'john.doe@example.com',
    'phone' => '123-456-7890'
];

header('Content-Type: application/json');

echo json_encode($responseData);
