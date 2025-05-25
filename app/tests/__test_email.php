<?php
echo "<pre>";

require_once __DIR__ . '/../models/Contact.php';

// query Test
$contact = Contact::findBy('name', 'Test');

echo "<h2>Info of Contact:</h2>";
print_r($contact->getAssoc());

echo "<h3>Sending Test Email...</h3>";
try {
    if ($contact->sendHelloEmail()) {
        echo "<h3 style='color: green'>Email Sent!</h3>";
    } else {
        echo "<h3 style='color: red'>Failed to send Email.</h3>";
    }
} catch (Exception $e) {
    echo "<h3 style='color: red'>Error: " . $e->getMessage() . "</h3>";
}
