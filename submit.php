<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/models/Contact.php';
require_once __DIR__ . '/app/helpers/Mailer.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    $submittedToken = $_POST['csrf_token'] ?? '';
    $sessionToken = $_SESSION['csrf_token'] ?? '';

    if (!$submittedToken || $submittedToken !== $sessionToken) {
        $response = ['message' => 'Invalid CSRF token. Please try again.'];
        http_response_code(403);
        echo json_encode($response);
        exit;
    }

    // Sanitize input
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    if ($name && filter_var($email, FILTER_VALIDATE_EMAIL) && $message) {
        try {
            // Save to database
            $contact = new Contact();
            $contact->setName($name);
            $contact->setEmail($email);
            $contact->setMessage($message);

            if (!$contact->insert()) {
                throw new Exception('Failed to save contact to database.');
            }

            // Send email to admin
            $smtpConfig = require 'app/config/smtp.php';
            $adminMailer = new Mailer();
            $adminMailer->setSubject('New Contact Form Submission');
            $adminMailer->setBody("
                <h1>New Contact Form Submission</h1>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Message:</strong> $message</p>
            ");
            $adminRecipient = $smtpConfig['recipient_email'] ?? 'aclc-tabulation@localhost.net'; // Updated fallback
            error_log("Admin recipient email: " . var_export($adminRecipient, true)); // Debug log
            if (!is_string($adminRecipient) || empty($adminRecipient) || !str_contains($adminRecipient, '@')) {
                throw new Exception('Invalid admin recipient email in SMTP configuration: ' . $adminRecipient);
            }
            $adminMailer->addRecipient($adminRecipient);
            $adminSent = $adminMailer->send();

            // Send confirmation email to user
            $userMailer = new Mailer();
            $userMailer->setSubject('Thank You for Contacting ACLC Tabulation Team');
            $userMailer->setBody("
                <h1>Hello $name!</h1>
                <p>Thank you for reaching out to us. We have received your message:</p>
                <p><strong>Message:</strong> $message</p>
                <p>We will get back to you soon at $email.</p>
            ");
            $userMailer->addRecipient($email);
            $userSent = $userMailer->send();

            if ($adminSent && $userSent) {
                $response = ['message' => 'Thank you for your message! We will get back to you soon.'];
                http_response_code(200);
            } else {
                throw new Exception('Email sending partially failed.');
            }
            http_response_code(200);
        } catch (Exception $e) {
            error_log("Exception caught: " . $e->getMessage());
            $response = ['message' => 'An error occurred: ' . $e->getMessage()];
            http_response_code(500);
        }
    } else {
        $response = ['message' => 'Please fill out all fields correctly.'];
        http_response_code(400);
    }
} else {
    $response = ['message' => 'Invalid request method.'];
    http_response_code(405);
}

echo json_encode($response);
