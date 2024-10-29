<?php

// Include your Database and WhatsappInfo classes
require_once '../config/Database.php';
require_once '../models/WhatsappInfo.php';

// Initialize the Database and get the connection
$database = new Database();
$db = $database->connect();

// Check the database connection
if ($db === null) {
    echo "Failed to connect to the database.";
    exit;
}

// Initialize the WhatsappInfo class with the connection
$whatsappInfo = new WhatsappInfo($db);

// Set properties for posting data


// Retrieve JSON data from the incoming webhook
$inputData = file_get_contents('php://input');

// Log the raw incoming data for debugging
echo "Webhook hit successfully.\n";
echo "Raw input data: " . $inputData . "\n";

// Decode the JSON data
$data = json_decode($inputData, true);

// Check if decoding was successful
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Invalid JSON received: " . json_last_error_msg();
    exit;
}

// Extract relevant fields from the decoded data
if (isset($data['instanceId'], $data['event'], $data['data'])) {
    $instanceId = $data['instanceId'];
    $eventName = $data['event'];
    $eventData = $data['data'];

    // Log the extracted data
    echo "Instance ID: $instanceId\n";
    echo "Event Name: $eventName\n";
    echo "Event Data: " . print_r($eventData, true) . "\n";

    // Check if the event is a message
    if ($eventName === 'message') {
        echo "Handle message event...\n";

        // Get the message data
        $messageData = $eventData['message'];

        // Get the message type
        $messageType = $messageData['type'];

        if ($messageType === 'chat') {
            $messageSenderId = $messageData['from']; // unique WhatsApp ID
            $messageCreatedAt = date('Y-m-d H:i:s', $messageData['timestamp']); // Convert timestamp to date
            $messageContent = $messageData['body'];

            // Extract the phone number from the message sender ID
            $messageSenderPhoneNumber = str_replace('@c.us', '', $messageSenderId);

            // Log the received message data
            echo "Sender Phone Number: $messageSenderPhoneNumber\n";
            echo "Message Content: $messageContent\n";
            echo "Message Created At: $messageCreatedAt\n";

            // Set properties for posting data
            $whatsappInfo->PhoneNumber = $messageSenderPhoneNumber;
            $whatsappInfo->Message = $messageContent;
            $whatsappInfo->Timestamp = $messageCreatedAt;

            // Insert data into the database
            if ($whatsappInfo->postData()) {
                // Set response code to 201 Created
                http_response_code(201);
                echo "Record created successfully.";
            } else {
                // Set response code to 500 Internal Server Error
                http_response_code(500);
                echo "Failed to create record.";
            }
        } else {
            // Set response code to 400 Bad Request
            http_response_code(400);
            echo "Unsupported message type.";
        }
    } else {
        // Set response code to 400 Bad Request
        http_response_code(400);
        echo "Cannot handle this event: $eventName";
        exit;
    }
} else {
    // Set response code to 400 Bad Request
    http_response_code(400);
    echo "Required fields are missing in the webhook data.";
    exit;
}
