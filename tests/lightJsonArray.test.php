<?php
include __DIR__.'/lightJsonArray.test.generateTestData.php';
include __DIR__.'/../lightJsonArray.php';

class LightJsonArrayTest extends PHPUnit_Framework_TestCase {
    protected $lightJsonArray;
    protected function getLightJsonArray(){
        global $jsonDataFileName;
        if($this->lightJsonArray) { return $this->lightJsonArray; }
        return new LightJsonArray($this->lightJsonArray = file_get_contents($jsonDataFileName));
    }

    public function testBehaviorIfArrayEmpty(){
        $lightJsonArray = new LightJsonArray();
        $this->assertEquals(0, count($lightJsonArray));
        $lightJsonArray = new LightJsonArray('[]');
        $this->assertEquals(0, count($lightJsonArray));
        $lightJsonArray = null;
    }

    public function testBehaviorIfArrayAlmostEmpty(){
        $lightJsonArray = new LightJsonArray('[23]');
        $this->assertEquals(1, count($lightJsonArray));
        $this->assertEquals(23, $lightJsonArray[0]);
        $lightJsonArray = null;
    }

    public function testCount(){
        global $jsonDataFileName, $longHashesJsonDataFileName;
        $lightJsonArray = $this->getLightJsonArray();
        $this->assertEquals(30001, count(json_decode(file_get_contents($jsonDataFileName))));
        $this->assertEquals(30001, count($lightJsonArray));
        $this->assertEquals(100002, count(json_decode(file_get_contents($longHashesJsonDataFileName))));
        $this->assertEquals(100002, count(new LightJsonArray(file_get_contents($longHashesJsonDataFileName))));
        $lightJsonArray = null;
    }

    public function testGetValueWithKey(){
        $lightJsonArray = $this->getLightJsonArray();
        $this->assertEquals(-1, $lightJsonArray[0]);
        $this->assertEquals(true, $lightJsonArray[2000]);
        $this->assertEquals(null, $lightJsonArray[5000]);
        $this->assertEquals(false, $lightJsonArray[7000]);
        $this->assertEquals('value1', $lightJsonArray[9000][0][0]);
        $this->assertEquals(false, $lightJsonArray[9000][0][1]);
        $this->assertEquals('how?', $lightJsonArray[9000][2]);

        $this->assertEquals(12, $lightJsonArray[9000][1][0]);
        $this->assertEquals(true, $lightJsonArray[9000][1][1]);
        $this->assertEquals(8.5, $lightJsonArray[9000][1][2]);
        $this->assertEquals('b', $lightJsonArray[9000][1][3]['a']);
        $this->assertEquals('d', $lightJsonArray[9000][1][3]['c']);
        $this->assertEquals(4, $lightJsonArray[9000][1][3]['e'][0]);
        $this->assertEquals(32.3, $lightJsonArray[9000][1][3]['e'][1]);
        $this->assertEquals(true, $lightJsonArray[9000][1][3]['e'][2]);;
        $this->assertNull($lightJsonArray[9000][1][4]);

        $this->assertEquals('', $lightJsonArray[10000]);
        $this->assertEquals('value1', $lightJsonArray[15000][0]);
        $this->assertEquals(false, $lightJsonArray[15000][1]);
        $this->assertEquals(12, $lightJsonArray[15000][2]);
        $this->assertEquals(true, $lightJsonArray[15000][3]);
        $this->assertEquals(8.5, $lightJsonArray[15000][4]);
        $this->assertEquals(null, $lightJsonArray[15000][5]);
        $this->assertEquals('how?', $lightJsonArray[15000][6]);
        $this->assertEquals('testValue', $lightJsonArray[20000]);
        $this->assertEquals('testValue1', $lightJsonArray[25000]['keyTest1']);
        $this->assertEquals(22, $lightJsonArray[25000]['keyTest2']);
        $this->assertEquals('testValue3', $lightJsonArray[25000]['keyTest3']);
        $this->assertEquals('_9999999999', $lightJsonArray[30000]['373904832']);
        $lightJsonArray = null;
    }

    public function testGetValuesInLoop(){
        $lightJsonArray = $this->getLightJsonArray();
        foreach ($lightJsonArray as $index => $value) {
            switch ($index) {
                case 0: $this->assertEquals(-1, $value); break;
                case 200000: $this->assertEquals(true, $value); break;
                case 500000: $this->assertEquals(null, $value); break;
                case 700000: $this->assertEquals(false, $value); break;
                case 1000000: $this->assertEquals('', $value); break;
                case 2000000: $this->assertEquals('testValue', $value); break;
                case 15000:
                    $this->assertEquals('value1', $value[0]);
                    $this->assertEquals(false, $value[1]);
                    $this->assertEquals(12, $value[2]);
                    $this->assertEquals(true, $value[3]);
                    $this->assertEquals(8.5, $value[4]);
                    $this->assertEquals(null, $value[5]);
                    $this->assertEquals('how?', $value[6]); break;
                case 25000:
                    $this->assertEquals('testValue1', $value['keyTest1']);
                    $this->assertEquals(22, $value['keyTest2']);
                    $this->assertEquals('testValue3', $value['keyTest3']); break;
                case 30000:
                    $this->assertEquals('_9999999999', $value[373904832]);
            }
        }
        $lightJsonArray = null;
    }

    public function testSetValueWithKey(){
        $lightJsonArray = $this->getLightJsonArray();
        $this->assertNotEquals('new test!', $lightJsonArray[12232]);
        $lightJsonArray[12232] = 'new test!';
        $this->assertEquals('new test!', $lightJsonArray[12232]);

        $this->assertArrayNotHasKey(30001, $lightJsonArray);
        $lightJsonArray[] = array('hi' => 'hou!');
        $this->assertArrayHasKey(30001, $lightJsonArray);
        $this->assertEquals('hou!', $lightJsonArray[30001]['hi']);

        $this->assertArrayNotHasKey(30010, $lightJsonArray);
        $lightJsonArray[30010] = array('newKey' => 'hasValue');
        $this->assertArrayHasKey(30010, $lightJsonArray);
        for ($i=3000002; $i < 30010; $i++)
            $this->assertNull($lightJsonArray[$i]);
        $this->assertEquals('hasValue', $lightJsonArray[30010]['newKey']);

        $this->assertEquals(-1, $lightJsonArray[0]);
        $this->assertEquals(true, $lightJsonArray[2000]);
        $this->assertEquals(null, $lightJsonArray[5000]);
        $this->assertEquals(false, $lightJsonArray[7000]);
        $this->assertEquals('', $lightJsonArray[10000]);
        $this->assertEquals('value1', $lightJsonArray[15000][0]);
        $this->assertEquals(false, $lightJsonArray[15000][1]);
        $this->assertEquals(12, $lightJsonArray[15000][2]);
        $this->assertEquals(true, $lightJsonArray[15000][3]);
        $this->assertEquals(8.5, $lightJsonArray[15000][4]);
        $this->assertEquals(null, $lightJsonArray[15000][5]);
        $this->assertEquals('how?', $lightJsonArray[15000][6]);
        $this->assertEquals('testValue', $lightJsonArray[20000]);
        $this->assertEquals('testValue1', $lightJsonArray[25000]['keyTest1']);
        $this->assertEquals(22, $lightJsonArray[25000]['keyTest2']);
        $this->assertEquals('testValue3', $lightJsonArray[25000]['keyTest3']);
        $this->assertEquals('_9999999999', $lightJsonArray[30000]['373904832']);
        $lightJsonArray = null;
    }

    public function testOffsetUnset(){
        $lightJsonArray = $this->getLightJsonArray();
        $this->assertEquals(-1, $lightJsonArray[0]);
        $this->assertEquals(true, $lightJsonArray[2000]);
        $this->assertEquals(null, $lightJsonArray[5000]);
        $this->assertEquals(false, $lightJsonArray[7000]);
        $this->assertEquals('', $lightJsonArray[10000]);
        $this->assertEquals('value1', $lightJsonArray[15000][0]);
        $this->assertEquals(false, $lightJsonArray[15000][1]);
        $this->assertEquals(12, $lightJsonArray[15000][2]);
        $this->assertEquals(true, $lightJsonArray[15000][3]);
        $this->assertEquals(8.5, $lightJsonArray[15000][4]);
        $this->assertEquals(null, $lightJsonArray[15000][5]);
        $this->assertEquals('how?', $lightJsonArray[15000][6]);
        $this->assertEquals('testValue', $lightJsonArray[20000]);
        $this->assertEquals('testValue1', $lightJsonArray[25000]['keyTest1']);
        $this->assertEquals(22, $lightJsonArray[25000]['keyTest2']);
        $this->assertEquals('testValue3', $lightJsonArray[25000]['keyTest3']);
        $this->assertEquals('_9999999999', $lightJsonArray[30000]['373904832']);
        foreach (array(2000, 5000, 7000, 10000, 15000, 20000, 25000,
            30000) as $index) {
            $lightJsonArray[$index] = null;
        }
        $this->assertNull($lightJsonArray[2000]);
        $this->assertNull($lightJsonArray[5000]);
        $this->assertNull($lightJsonArray[7000]);
        $this->assertNull($lightJsonArray[10000]);
        $this->assertNull($lightJsonArray[15000]);
        $this->assertNull($lightJsonArray[20000]);
        $this->assertNull($lightJsonArray[25000]);
        $this->assertNull($lightJsonArray[30000]);
        foreach (array(200000, 500000, 700000, 1000000, 15000, 2000000, 25000,
            30000) as $index) {
            unset($lightJsonArray[$index]);
        }
        $this->assertNull($lightJsonArray[2000]);
        $this->assertNull($lightJsonArray[5000]);
        $this->assertNull($lightJsonArray[7000]);
        $this->assertNull($lightJsonArray[10000]);
        $this->assertNull($lightJsonArray[15000]);
        $this->assertNull($lightJsonArray[20000]);
        $this->assertNull($lightJsonArray[25000]);
        $this->assertNull($lightJsonArray[30000]);
        $lightJsonArray = null;
    }
}
