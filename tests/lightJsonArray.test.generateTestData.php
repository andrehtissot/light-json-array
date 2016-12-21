<?php
ini_set("memory_limit","1024M");
if(!is_dir(__DIR__.'/tmp')) mkdir(__DIR__.'/tmp');
$jsonDataFileName = __DIR__.'/tmp/lightJsonArray.test.json';
if(!file_exists($jsonDataFileName)){
    echo "Test data file not found!\nGenerating a new one...";
    $json = '[-1,';
    for ($i=0; $i < 1999; $i++)
        $json .= '"_'.rand(10000000,99999999).'",';
    $json .= 'true,';
    for ($i=0; $i < 2999; $i++)
        $json .= '"_'.rand(10000000,99999999).'",';
    $json .= 'null,';
    for ($i=0; $i < 1999; $i++)
        $json .= '"_'.rand(10000000,99999999).'",';
    $json .= 'false,';
    for ($i=0; $i < 1999; $i++)
        $json .= '"_'.rand(10000000,99999999).'",';
    $json .= '[["value1",false],[12,true,8.5,{"a":"b","c":"d","e":[4,32.3,true]},null],"how?"],';
    // $json .= '[["value1",false],[12,true,8.5,null],"how?"],';
    // $json .= '["value1",false,12,true,8.5,"a","b","c","d","e",4,32.3,true,null,"how?"],';
    // $json .= '"test",';
    for ($i=0; $i < 999; $i++)
        $json .= '"_'.rand(10000000,99999999).'",';
    $json .= '"",';
    for ($i=0; $i < 4999; $i++)
        $json .= rand(10000000,99999999).',';
    $json .= '["value1",false,12,true,8.5,null,"how?"],';
    // $json .= '"test",';
    for ($i=0; $i < 4999; $i++)
        $json .= rand(10000000,99999999).',';
    $json .= '"testValue",';
    for ($i=0; $i < 4999; $i++)
        $json .= '{"attr1":'.rand(1000,9999).',"attr2":"_'.rand(10000000,99999999).'_"},';
    $json .= '{"keyTest1":"testValue1","keyTest2":22,"keyTest3":"testValue3"},';
    for ($i=0; $i < 4999; $i++)
        $json .= '{"attrX":'.rand(1000,9999).',"attrY":"_'.rand(10000000,99999999).'_"},';
    $json .= '{"373904832":"_9999999999"}]';
    file_put_contents($jsonDataFileName, $json);
    $json = null;
    echo "\rTest data file generated!   \n";
}

$longHashesJsonDataFileName = __DIR__.'/tmp/lightJsonArray.longHashes.json';
if(!file_exists($longHashesJsonDataFileName)){
    echo "Long hashes data file not found!\nGenerating a new one...";
    $json = '[-1,null,';
    for ($i=0; $i < 99999; $i++){
        $json .= '{';
        for ($j=0; $j < 30; $j++)
            $json .= "\"attribute$j\":\"_".rand(10000000,99999999).'_",';
        $json .= "\"attribute$j\":\"_".rand(10000000,99999999).'_"},';
    }
    $json .= '{"373904832":"_9999999999"}]';
    file_put_contents($longHashesJsonDataFileName, $json);
    $json = null;
    echo "\rLong hashes data file generated!   \n";
}
