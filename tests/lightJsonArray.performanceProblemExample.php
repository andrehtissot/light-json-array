<?php

include __DIR__.'/lightJsonArray.test.generateTestData.php';
include __DIR__.'/../lightJsonArray.php';

foreach (array($jsonDataFileName => 'Test Data', $longHashesJsonDataFileName => 'Long Hashes Data') as $jsonSourceFileName => $title) {
    echo "# Using $title Example (".count(json_decode(file_get_contents($jsonSourceFileName)))
        ." elements):\n";
    echo "\nTests:\n";
    $currentRamCost = memory_get_usage(true);
    $jsonData = file_get_contents($jsonSourceFileName);
    $jsonDataRamCost = (memory_get_usage(true)-$currentRamCost)/1048576;
    echo 'Test data memory cost as JSON String: ' . number_format($jsonDataRamCost, 2)." MB.\n";
    $jsonData = null;

    $currentRamCost = memory_get_usage(true);
    $dataAsArray = json_decode(file_get_contents($jsonSourceFileName), true);
    $dataAsArrayRamCost = (memory_get_usage(true)-$currentRamCost)/1048576;
    echo 'Test data memory cost as decoded Array: ' . number_format($dataAsArrayRamCost, 2)." MB.\n";
    $dataAsArray = null;

    $currentRamCost = memory_get_usage(true);
    $dataAsObject = json_decode(file_get_contents($jsonSourceFileName), true);
    $dataAsObjectRamCost = (memory_get_usage(true)-$currentRamCost)/1048576;
    echo 'Test data memory cost as decoded Object: ' . number_format($dataAsObjectRamCost, 2)." MB.\n";
    $dataAsObject = null;

    $currentRamCost = memory_get_usage(true);
    $dataAsLightJsonArray = new LightJsonArray(file_get_contents($jsonSourceFileName));
    $dataAsLightJsonArrayRamCost = (memory_get_usage(true)-$currentRamCost)/1048576;
    echo 'Test data memory cost as LightJsonArray: ' . number_format($dataAsLightJsonArrayRamCost, 2)." MB.\n";
    $dataAsLightJsonArray = null;

    echo "\nResults:\n";
    if(intval($jsonDataRamCost) === intval($dataAsLightJsonArrayRamCost))
        echo "LightJsonArray offers a array access with near ZERO memory cost!\n";
    if($dataAsArrayRamCost > 0.0000001)
        echo 'LightJsonArray cost '.number_format(100*$dataAsLightJsonArrayRamCost/$dataAsArrayRamCost, 2)
            ."% of what decoded Array cost\n";
    if($dataAsObjectRamCost > 0.0000001)
        echo 'LightJsonArray cost '.number_format(100*$dataAsLightJsonArrayRamCost/$dataAsObjectRamCost, 2)
            ."% of what decoded Object cost\n";
    echo "\n\n";
}

