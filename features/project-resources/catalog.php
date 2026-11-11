<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');

$root = realpath(dirname(__DIR__, 2));
if ($root === false) {
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo localizar la carpeta del proyecto.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$categories = [
    'srs' => 'Anexos/SRS',
    'mer' => 'Anexos/MER',
    'pac' => 'PAC',
    'quality' => 'Herramientas de calidad',
    'user_manual' => 'Anexos/Manuales/Usuario',
    'technical_manual' => 'Anexos/Manuales/Tecnico',
];
$allowedExtensions = [
    'pdf', 'drawio', 'doc', 'docx', 'odt', 'rtf',
    'xls', 'xlsx', 'ods', 'csv', 'ppt', 'pptx', 'odp',
    'png', 'jpg', 'jpeg', 'gif', 'webp',
];
$result = [];

foreach ($categories as $id => $relativeDirectory) {
    $result[$id] = [];
    $directory = realpath($root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativeDirectory));
    if ($directory === false || !is_dir($directory)) continue;

    foreach (new DirectoryIterator($directory) as $entry) {
        if ($entry->isDot() || !$entry->isFile() || $entry->isLink()) continue;

        $extension = strtolower($entry->getExtension());
        if (!in_array($extension, $allowedExtensions, true)) continue;

        $path = realpath($entry->getPathname());
        if ($path === false || dirname($path) !== $directory) continue;

        $relativePath = $relativeDirectory . '/' . $entry->getFilename();
        $encodedPath = implode('/', array_map('rawurlencode', explode('/', $relativePath)));
        $name = $entry->getBasename('.' . $entry->getExtension());
        if ($id === 'srs' && stripos($name, 'v2.0') !== false) {
            $name = 'SRS 2.0';
        } elseif ($id === 'srs' && stripos($name, 'SRS') !== false) {
            $name = 'SRS original';
        } elseif ($id === 'mer' && stripos($name, 'MER') !== false) {
            $name = 'Modelo entidad-relación';
        }

        $result[$id][] = [
            'name' => $name,
            'extension' => $extension,
            'url' => $encodedPath,
        ];
    }

    usort($result[$id], static fn(array $left, array $right): int => strnatcasecmp($left['name'], $right['name']));
}

echo json_encode(['categories' => $result], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
