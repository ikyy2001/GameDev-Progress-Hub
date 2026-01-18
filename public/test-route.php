<?php
// Test routing untuk debug
$request_uri = $_SERVER['REQUEST_URI'] ?? '/projects/1';
$request_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

echo "Request URI: $request_uri\n";
echo "Request Method: $request_method\n";

$uri = parse_url($request_uri, PHP_URL_PATH);
echo "Parsed URI: $uri\n";

$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
echo "Script Name: $script_name\n";

if ($script_name !== '/') {
    $uri = str_replace($script_name, '', $uri);
}

$uri = trim($uri, '/');
echo "Final URI: $uri\n";

$pattern = 'GET:/projects/{id}';
$pattern_path = preg_replace('/^[A-Z]+:\//', '', $pattern);
$pattern_parts = array_values(array_filter(explode('/', trim($pattern_path, '/'))));
$uri_parts = array_values(array_filter(explode('/', $uri)));

echo "\nPattern: $pattern_path\n";
echo "Pattern Parts: " . implode(', ', $pattern_parts) . "\n";
echo "URI Parts: " . implode(', ', $uri_parts) . "\n";
echo "Count Match: " . (count($pattern_parts) === count($uri_parts) ? 'Yes' : 'No') . "\n";

if (count($pattern_parts) === count($uri_parts)) {
    $params = [];
    $match = true;
    
    for ($i = 0; $i < count($pattern_parts); $i++) {
        if (preg_match('/\{(\w+)\}/', $pattern_parts[$i], $matches)) {
            $params[$matches[1]] = $uri_parts[$i];
            echo "Found param: {$matches[1]} = {$uri_parts[$i]}\n";
        } elseif ($pattern_parts[$i] !== $uri_parts[$i]) {
            $match = false;
            echo "Part mismatch: {$pattern_parts[$i]} !== {$uri_parts[$i]}\n";
            break;
        }
    }
    
    echo "\nMatch: " . ($match ? 'Yes' : 'No') . "\n";
    if ($match) {
        echo "Params: " . print_r($params, true) . "\n";
    }
}

