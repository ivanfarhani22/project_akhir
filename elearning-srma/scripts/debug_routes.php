<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';

$router = $app->make('router');
$out = [];
foreach ($router->getRoutes() as $r) {
    $uri = $r->uri();
    if (strpos($uri, 'guru/attendance') !== false) {
        $out[] = [
            'methods' => implode(',', $r->methods()),
            'uri' => $uri,
            'name' => $r->getName(),
            'action' => $r->getActionName(),
            'middleware' => $r->gatherMiddleware(),
        ];
    }
}

echo json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), PHP_EOL;
