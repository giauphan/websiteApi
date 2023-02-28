<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>JSON Data</title>
</head>
<body>
    <pre>
        {{ json_encode($jsonData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}
    </pre>
    <pre>
        {{ print_r($arr) }}
    </pre>
</body>
</html>
