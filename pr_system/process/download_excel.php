<?php
require_once '../db.php'; 

$category = $_GET['cat'] ?? 'catering';
$table_map = [
    'catering'        => 'catering_records',
    'office_supplies' => 'office_supplies',
    'ict_devices'     => 'ict_devices',
    'furnitures'      => 'furnitures',
    'fabrication'     => 'fabrication_installation',
    'heavy_equipment' => 'heavy_equipment',
    'appliances'      => 'appliances', 
    'fixtures'        => 'fixtures'    
];

$current_table = $table_map[$category] ?? 'catering_records';
$filename = $category . "_data_" . date('Y-m-d') . ".csv";

try {
    $date_col = ($current_table == 'catering_records') ? 'catering_date' : 'transaction_date';
    $stmt = $pdo->prepare("SELECT * FROM $current_table ORDER BY $date_col DESC");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

    if (!empty($rows)) {
        fputcsv($output, array_map('strtoupper', array_keys($rows[0])));
        foreach ($rows as $row) { fputcsv($output, $row); }
    }
    fclose($output);
    exit;
} catch (Exception $e) { die($e->getMessage()); }