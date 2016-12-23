<?php

include __DIR__.'/lightJsonArray.test.generateTestData.php';
include __DIR__.'/../lightJsonArray.php';

foreach (array($jsonDataFileName => 'Test Data', $longHashesJsonDataFileName => 'Long Hashes Data')
    as $jsonSourceFileName => $title) {

    $fileContentSource = file_get_contents($jsonSourceFileName);
    echo "# Using $title Example (".count(json_decode($fileContentSource))
        ." elements):\n";

    echo "\nTests:\n";
    $currentRamCost = memory_get_usage(true);
    $lastTick = microtime(true);
    $jsonData = ''.$fileContentSource;
    $jsonDataRamCost = (memory_get_usage(true)-$currentRamCost)/1048576;
    $jsonDataTimeCost = intval((microtime(true) - $lastTick)*100)/100;
    echo $title . ' memory cost as JSON String was ' . number_format($jsonDataRamCost, 2)
        ." MB and took $jsonDataTimeCost seconds.\n";
    $jsonData = null;

    $currentRamCost = memory_get_usage(true);
    $lastTick = microtime(true);
    $dataAsArray = json_decode(''.$fileContentSource, true);
    foreach ($dataAsArray as $index => $value) {}
    $dataAsArrayRamCost = (memory_get_usage(true)-$currentRamCost)/1048576;
    $dataAsArrayTimeCost = intval((microtime(true) - $lastTick)*100)/100;
    echo $title . ' memory cost as decoded Array was ' . number_format($dataAsArrayRamCost, 2)
        ." MB and took $dataAsArrayTimeCost seconds.\n";
    $dataAsArray = null;

    $currentRamCost = memory_get_usage(true);
    $lastTick = microtime(true);
    $dataAsObject = json_decode(''.$fileContentSource, true);
    foreach ($dataAsObject as $index => $value) {}
    $dataAsObjectRamCost = (memory_get_usage(true)-$currentRamCost)/1048576;
    $dataAsObjectTimeCost = intval((microtime(true) - $lastTick)*100)/100;
    echo $title . ' memory cost as decoded Object was ' . number_format($dataAsObjectRamCost, 2)
        ." MB and took $dataAsObjectTimeCost seconds.\n";
    $dataAsObject = null;

    $currentRamCost = memory_get_usage(true);
    $lastTick = microtime(true);
    $dataAsLightJsonArray = new LightJsonArray(''.$fileContentSource);
    foreach ($dataAsLightJsonArray as $index => $value) {}
    $dataAsLightJsonArrayRamCost = (memory_get_usage(true)-$currentRamCost)/1048576;
    $dataAsLightJsonArrayTimeCost = intval((microtime(true) - $lastTick)*100)/100;
    echo $title . ' memory cost as LightJsonArray was ' . number_format($dataAsLightJsonArrayRamCost, 2)
        ." MB and took $dataAsLightJsonArrayTimeCost seconds.\n";
    $dataAsLightJsonArray = null;

    echo "\nResults:\n";
    if(intval($jsonDataRamCost) === intval($dataAsLightJsonArrayRamCost))
        echo "LightJsonArray offers a array access with near ZERO memory cost!\n";
    if($dataAsArrayRamCost > 0.0000001)
        echo 'LightJsonArray cost '.number_format(100*$dataAsLightJsonArrayRamCost/$dataAsArrayRamCost, 2)
            ."% of what decoded Array cost.\n";
    if($dataAsArrayTimeCost > 0.0000001)
        echo 'LightJsonArray took '.number_format($dataAsLightJsonArrayTimeCost/$dataAsArrayTimeCost, 2)
            ." times of what decoded Array took.\n";
    if($dataAsObjectRamCost > 0.0000001)
        echo 'LightJsonArray cost '.number_format(100*$dataAsLightJsonArrayRamCost/$dataAsObjectRamCost, 2)
            ."% of what decoded Object cost.\n";
    if($dataAsObjectTimeCost > 0.0000001)
        echo 'LightJsonArray took '.number_format($dataAsLightJsonArrayTimeCost/$dataAsObjectTimeCost, 2)
            ." times of what decoded Object took.\n";
    echo "\n\n";
}
