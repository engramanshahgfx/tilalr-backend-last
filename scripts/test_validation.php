<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Validator;

$data = [
    'first_name' => 'John',
    'last_name' => 'Doe',
    'email' => 'john.doe@example.com',
    'mobile' => '0501234567',
    'travel_date' => '2026-08-01',
    'room_type' => 'DoubleRoom',
    'package_id' => '1',
    'package_code' => 'PKG-1',
    'notes' => '',
    'payment_method' => 'credit_card',
    'booking_type' => 'destination',
    'guests' => 1,
    'special_requests' => '',
    'total_amount' => 1800,
    'price' => 1800,
];

$validator = Validator::make($data, [
    'first_name' => 'required|string|max:255',
    'last_name' => 'required|string|max:255',
    'email' => 'required|email|max:255',
    'mobile' => 'required|string|max:20',
    'travel_date' => 'required|date_format:Y-m-d',
    'room_type' => 'required|in:DoubleRoom,SingleRoom',
    'package_id' => 'nullable|string',
    'package_code' => 'nullable|string',
    'package_title' => 'nullable|string',
    'notes' => 'nullable|string',
    'payment_method' => 'nullable|in:credit_card,bank_transfer',
    'booking_type' => 'nullable|in:destination,tourism_offer',
    'guests' => 'nullable|integer|min:1',
    'special_requests' => 'nullable|string',
    'total_amount' => 'nullable|numeric|min:0',
]);

if ($validator->fails()) {
    echo "FAILED:\n";
    print_r($validator->errors()->toArray());
} else {
    echo "SUCCESS\n";
}
