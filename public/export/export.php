<?php
require_once __DIR__ . '/../../src/models/Material.php';

$format = $_GET['format'] ?? 'pdf';

// récupérer tous les filtres dynamiquement
$filters = $_GET;
unset($filters['format'], $filters['source']);

// données
$data = array_filter($filters)
    ? Material::getFiltered($filters)
    : Material::getAll();

if ($format === 'pdf') {
    require __DIR__ . '/export_pdf.php';
} else {
    require __DIR__ . '/export_excel.php';
}
