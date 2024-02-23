<?php

//  content type to json
header('Content-Type: application/json');

// Function to ping an IP address and return the result
function ping($ip) {
    // Use exec to run the ping command
    exec(sprintf('ping -c 1 -W 1 %s', escapeshellarg($ip)), $res, $rval);

    // Check if ping was successful
    if ($rval === 0) {
        return array(
            'ip' => $ip,
            'status' => 'Online',
            'response_time' => substr($res[5], strpos($res[5], '=')+1)
        );
    } else {
        return array(
            'ip' => $ip,
            'status' => 'Offline',
            'response_time' => null
        );
    }
}

// Check if the IP addresses are provided in the URL
if (isset($_GET['router']) && isset($_GET['sm'])) {
    // Get the IP addresses from the URL
    $routerIP = $_GET['router'];
    $smIP = $_GET['sm'];

    // Ping the specified IP addresses
    $routerPingResult = ping($routerIP);
    $smPingResult = ping($smIP);

    // Output the ping results as JSON
    echo json_encode(array(
        'router' => $routerPingResult,
        'sm' => $smPingResult
    ));
} else {
    // IP addresses not provided, return an error
    echo json_encode(array('error' => 'IP addresses not provided'));
}


Second option 
 <?php

// Set headers to return JSON
header('Content-Type: application/json');

// Function to execute ping command and return result
function getPingResult($ip) {
    $pingResult = shell_exec("ping -c 5 -W 1 $ip");
    // Replace newlines and format ping results
    return preg_replace(array('/\n/m', '/(\d+% packet loss, time \d+\w+)/'), array('<br />', '<strong>$1</strong>'), $pingResult);
}

// Check if IP parameters are provided
if (isset($_GET['antip']) && isset($_GET['wanip'])) {
    $antPing = getPingResult($_GET['antip']);
    $wanPing = getPingResult($_GET['wanip']);

    // Return JSON response
    echo json_encode(array(
        'antenna' => $antPing,
        'router' => $wanPing
    ));
} else {
    // Return error if parameters are missing
    http_response_code(400);
    echo json_encode(array('error' => 'Missing IP parameters'));
}
?>
