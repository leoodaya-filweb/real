<?php
use app\widgets\PieChart;
use app\helpers\Html;

$total_voters = 0;
$data = []; // Initialize $data

foreach ($features as $key => $feature) {
    $total_voters += filter_var(str_replace(',', '', $feature['properties']['household']), FILTER_VALIDATE_FLOAT);

    foreach ($feature['properties']['household_colors'] as $hc) { 
        if ($hc['label'] === 'Gray') { // Only add Gray category
            $label = $hc['label'];
            $total = filter_var(str_replace(',', '', $hc['total']), FILTER_VALIDATE_FLOAT);

            $data[$label] = isset($data[$label]) ? $data[$label] + $total : $total;
        }

        if ($key == 0) {
            $barangay = $feature['properties']['barangay'];
        } else {
            $barangay = $barangay ?? 'All';
        }
    }
}

echo 'Barangay: ' . ($queryParams['barangay'] ?: 'All');
echo '<br/>Purok: ' . ($queryParams['purok'] ?: 'All');
echo '<br/>Total Swing Voters: ' . number_format($total_voters);
?>

<?= PieChart::widget([
    'width' => 330,
    'datasort' => false,
    'colors' => ['#e4e6ef'], // Keep only the gray color
    'data' => $data
]) ?>
