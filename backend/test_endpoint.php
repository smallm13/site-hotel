<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';

// Make sure we're using the HTTP kernel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a request that mimics what the AJAX call would send
$request = Illuminate\Http\Request::create('/api/reservations', 'POST', [
    '_token' => csrf_token(),
    'checkin' => '2026-05-25',
    'checkout' => '2026-05-26',
    'adults' => '1',
    'children' => '0',
    'room_type' => 'single',
    'first_name' => 'Test',
    'last_name' => 'User',
    'email' => 'test@example.com',
    'phone' => '+221 77 000 00 00'
]);

// Set headers to mimic AJAX request
$request->headers->set('X-Requested-With', 'XMLHttpRequest');
$request->headers->set('Content-Type', 'application/x-www-form-urlencoded');

// Handle the request
$response = $kernel->handle($request);

// Get the content
$content = $response->getContent();

// Output headers and content for debugging
echo "HTTP Status: ".$response->getStatusCode().PHP_EOL;
echo "Content-Type: ".$response->headers->get('Content-Type').PHP_EOL;
echo "Content: ".$content;
?>