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
        $lightJsonArray = $this->getLightJsonArray();
        $this->assertEquals(3000001, count($lightJsonArray));
        $lightJsonArray = null;
    }

    public function testGetValueWithKey(){
        $lightJsonArray = $this->getLightJsonArray();
        $this->assertEquals(-1, $lightJsonArray[0]);
        $this->assertEquals(true, $lightJsonArray[200000]);
        $this->assertEquals(null, $lightJsonArray[500000]);
        $this->assertEquals(false, $lightJsonArray[700000]);
        $this->assertEquals('', $lightJsonArray[1000000]);
        $this->assertEquals('value1', $lightJsonArray[1500000][0]);
        $this->assertEquals(false, $lightJsonArray[1500000][1]);
        $this->assertEquals(12, $lightJsonArray[1500000][2]);
        $this->assertEquals(true, $lightJsonArray[1500000][3]);
        $this->assertEquals(8.5, $lightJsonArray[1500000][4]);
        $this->assertEquals(null, $lightJsonArray[1500000][5]);
        $this->assertEquals('how?', $lightJsonArray[1500000][6]);
        $this->assertEquals('testValue', $lightJsonArray[2000000]);
        $this->assertEquals('testValue1', $lightJsonArray[2500000]['keyTest1']);
        $this->assertEquals(22, $lightJsonArray[2500000]['keyTest2']);
        $this->assertEquals('testValue3', $lightJsonArray[2500000]['keyTest3']);
        $this->assertEquals('_9999999999', $lightJsonArray[3000000]['373904832']);
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
                case 1500000:
                    $this->assertEquals('value1', $value[0]);
                    $this->assertEquals(false, $value[1]);
                    $this->assertEquals(12, $value[2]);
                    $this->assertEquals(true, $value[3]);
                    $this->assertEquals(8.5, $value[4]);
                    $this->assertEquals(null, $value[5]);
                    $this->assertEquals('how?', $value[6]); break;
                case 2500000:
                    $this->assertEquals('testValue1', $value['keyTest1']);
                    $this->assertEquals(22, $value['keyTest2']);
                    $this->assertEquals('testValue3', $value['keyTest3']); break;
                case 3000000:
                    $this->assertEquals('_9999999999', $value[373904832]);
            }
        }
        $lightJsonArray = null;
    }

    public function testSetValueWithKey(){
        $lightJsonArray = $this->getLightJsonArray();
        $this->assertNotEquals('new test!', $lightJsonArray[1223732]);
        $lightJsonArray[1223732] = 'new test!';
        $this->assertEquals('new test!', $lightJsonArray[1223732]);

        $this->assertArrayNotHasKey(3000001, $lightJsonArray);
        $lightJsonArray[] = array('hi' => 'hou!');
        $this->assertArrayHasKey(3000001, $lightJsonArray);
        $this->assertEquals('hou!', $lightJsonArray[3000001]['hi']);

        $this->assertArrayNotHasKey(3000010, $lightJsonArray);
        $lightJsonArray[3000010] = array('newKey' => 'hasValue');
        $this->assertArrayHasKey(3000010, $lightJsonArray);
        for ($i=3000002; $i < 3000010; $i++)
            $this->assertNull($lightJsonArray[$i]);
        $this->assertEquals('hasValue', $lightJsonArray[3000010]['newKey']);

        $this->assertEquals(-1, $lightJsonArray[0]);
        $this->assertEquals(true, $lightJsonArray[200000]);
        $this->assertEquals(null, $lightJsonArray[500000]);
        $this->assertEquals(false, $lightJsonArray[700000]);
        $this->assertEquals('', $lightJsonArray[1000000]);
        $this->assertEquals('value1', $lightJsonArray[1500000][0]);
        $this->assertEquals(false, $lightJsonArray[1500000][1]);
        $this->assertEquals(12, $lightJsonArray[1500000][2]);
        $this->assertEquals(true, $lightJsonArray[1500000][3]);
        $this->assertEquals(8.5, $lightJsonArray[1500000][4]);
        $this->assertEquals(null, $lightJsonArray[1500000][5]);
        $this->assertEquals('how?', $lightJsonArray[1500000][6]);
        $this->assertEquals('testValue', $lightJsonArray[2000000]);
        $this->assertEquals('testValue1', $lightJsonArray[2500000]['keyTest1']);
        $this->assertEquals(22, $lightJsonArray[2500000]['keyTest2']);
        $this->assertEquals('testValue3', $lightJsonArray[2500000]['keyTest3']);
        $this->assertEquals('_9999999999', $lightJsonArray[3000000]['373904832']);
        $lightJsonArray = null;
    }

    public function testOffsetUnset(){
        $lightJsonArray = $this->getLightJsonArray();
        $this->assertEquals(-1, $lightJsonArray[0]);
        $this->assertEquals(true, $lightJsonArray[200000]);
        $this->assertEquals(null, $lightJsonArray[500000]);
        $this->assertEquals(false, $lightJsonArray[700000]);
        $this->assertEquals('', $lightJsonArray[1000000]);
        $this->assertEquals('value1', $lightJsonArray[1500000][0]);
        $this->assertEquals(false, $lightJsonArray[1500000][1]);
        $this->assertEquals(12, $lightJsonArray[1500000][2]);
        $this->assertEquals(true, $lightJsonArray[1500000][3]);
        $this->assertEquals(8.5, $lightJsonArray[1500000][4]);
        $this->assertEquals(null, $lightJsonArray[1500000][5]);
        $this->assertEquals('how?', $lightJsonArray[1500000][6]);
        $this->assertEquals('testValue', $lightJsonArray[2000000]);
        $this->assertEquals('testValue1', $lightJsonArray[2500000]['keyTest1']);
        $this->assertEquals(22, $lightJsonArray[2500000]['keyTest2']);
        $this->assertEquals('testValue3', $lightJsonArray[2500000]['keyTest3']);
        $this->assertEquals('_9999999999', $lightJsonArray[3000000]['373904832']);
        foreach (array(200000, 500000, 700000, 1000000, 1500000, 2000000, 2500000,
            3000000) as $index) {
            $lightJsonArray[$index] = null;
        }
        $this->assertNull($lightJsonArray[200000]);
        $this->assertNull($lightJsonArray[500000]);
        $this->assertNull($lightJsonArray[700000]);
        $this->assertNull($lightJsonArray[1000000]);
        $this->assertNull($lightJsonArray[1500000]);
        $this->assertNull($lightJsonArray[2000000]);
        $this->assertNull($lightJsonArray[2500000]);
        $this->assertNull($lightJsonArray[3000000]);
        foreach (array(200000, 500000, 700000, 1000000, 1500000, 2000000, 2500000,
            3000000) as $index) {
            unset($lightJsonArray[$index]);
        }
        $this->assertNull($lightJsonArray[200000]);
        $this->assertNull($lightJsonArray[500000]);
        $this->assertNull($lightJsonArray[700000]);
        $this->assertNull($lightJsonArray[1000000]);
        $this->assertNull($lightJsonArray[1500000]);
        $this->assertNull($lightJsonArray[2000000]);
        $this->assertNull($lightJsonArray[2500000]);
        $this->assertNull($lightJsonArray[3000000]);
        $lightJsonArray = null;
    }
}
