<?php
function get_request_data() {
    $file = 'request_count.json';
    if (!file_exists($file)) {
        return ['count' => 0, 'timestamp' => time()];
    }

    $data = json_decode(file_get_contents($file), true);
    if (time() - $data['timestamp'] > 86400) {
        $data = ['count' => 0, 'timestamp' => time()];
    }

    return $data;
}

$data = get_request_data();
echo json_encode(['requests_left' => 200 - $data['count']]);
?>
