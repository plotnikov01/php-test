<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Lead</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<nav class="bg-white shadow mb-8">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex space-x-4">
                <a href="index.php" class="text-gray-800 font-bold">Add Lead</a>
                <a href="statuses.php" class="text-gray-600 hover:text-gray-800">Lead Statuses</a>
            </div>
        </div>
    </div>
</nav>

<div class="container mx-auto px-6">
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'firstName' => $_POST['firstName'] ?? '',
            'lastName' => $_POST['lastName'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['email'] ?? '',
            'countryCode' => 'GB',
            'box_id' => 28,
            'offer_id' => 5,
            'landingUrl' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",
            'ip' => $_SERVER['REMOTE_ADDR'],
            'password' => 'qwerty12',
            'language' => 'en'
        ];

        $ch = curl_init('https://crm.belmar.pro/api/v1/addlead');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'token: ba67df6a-a17c-476f-8e95-bcdb75ed3958'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);

        if ($result['status'] === true) {
            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">Lead added successfully!</div>';
        } else {
            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">Error: ' . ($result['error'] ?? 'Unknown error') . '</div>';
        }
    }
    ?>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Add New Lead</h1>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 mb-2" for="firstName">First Name *</label>
                <input type="text" id="firstName" name="firstName" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 mb-2" for="lastName">Last Name *</label>
                <input type="text" id="lastName" name="lastName" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 mb-2" for="phone">Phone *</label>
                <input type="tel" id="phone" name="phone" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 mb-2" for="email">Email *</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit"
                    class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Submit Lead
            </button>
        </form>
    </div>
</div>
</body>
</html>