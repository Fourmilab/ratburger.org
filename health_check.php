<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ratburger.org Health Check</title>
<meta name="robots" content="noindex,nofollow" />
</head>

<body>

<h1>Ratburger.org Health Check</h1>

<hr />

<p>
This document is retrieved by the Amazon Web Services Route 53
health check.  It is designed to minimise server resources
consumed while exercising PHP.
</p>

<?php
    echo("<p>Everything appears to be tickety-boo.</p>\n");
    echo("<p>Memory usage: " . memory_get_usage() . 
         "  Peak: " . memory_get_peak_usage() . "</p>\n");
         
    //  Example of returning HTTP failure status due to check
//    if (rand(0, 15) == 7) {
//        http_response_code(404);
//        http_response_code(503);    //  503: Service Unavailable
//    }
?>

<hr />

</body>
</html>
