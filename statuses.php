<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lead Statuses</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<nav class="bg-white shadow mb-8">
    <div class="container mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex space-x-4">
                <a href="index.php" class="text-gray-600 hover:text-gray-800">Add Lead</a>
                <a href="statuses.php" class="text-gray-800 font-bold">Lead Statuses</a>
            </div>
        </div>
    </div>
</nav>

<div class="container mx-auto px-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Lead Statuses</h1>

        <form method="GET" class="mb-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 mb-2" for="date_from">Date From</label>
                    <input type="datetime-local" id="date_from" name="date_from"
                           value="<?php echo $_GET['date_from'] ?? date('Y-m-d\TH:i', strtotime('-30 days')); ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2" for="date_to">Date To</label>
                    <input type="datetime-local" id="date_to" name="date_to"
                           value="<?php echo $_GET['date_to'] ?? date('Y-m-d\TH:i'); ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <button type="submit"
                    class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Filter Results
            </button>
        </form>

        <?php
        if (!empty($_GET)) {
            $data = [
                'date_from' => date('Y-m-d H:i:s', strtotime($_GET['date_from'] ?? '-30 days')),
                'date_to' => date('Y-m-d H:i:s', strtotime($_GET['date_to'] ?? 'now')),
                'page' => 0,
                'limit' => 100
            ];

            $ch = curl_init('https://crm.belmar.pro/api/v1/getstatuses');
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
                $leads = $result['data']; // No need to decode again
                ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">FTD</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($leads as $lead): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($lead['id']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($lead['email']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($lead['status']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($lead['ftd']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php
            } else {
                echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">Error: ' . ($result['error'] ?? 'Unknown error') . '</div>';
            }
        }
        ?>
    </div>
</div>
</body>
</html>