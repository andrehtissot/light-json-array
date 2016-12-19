<?php
class LightJsonArray implements ArrayAccess,SeekableIterator,Countable {
    protected $internalJson;
    protected $iteratorCurrentlyAtIndex = 0;
    protected $offsetsByIndex;
    protected $count = 0;

    public function __construct($jsonData = ''){
        $this->internalJson = $jsonData;
        if(empty($this->internalJson)) { return; }
        $this->setCountAndOffsetsByIndex();
    }
    public function count(){
        return $this->count;
    }
    public function offsetExists($index){
        return $index < $this->count;
    }
    public function offsetGet($index){
        if($index < $this->count)
            return json_decode(substr($this->internalJson, $this->offsetsByIndex[$index],
                $this->offsetsByIndex[$index+1]-$this->offsetsByIndex[$index]-1), true);
        throw new Exception("Notice: Undefined offset: ".$index);
    }
    public function seek($position){
        $this->iteratorCurrentlyAtIndex = $position;
    }
    public function current(){
        return $this->offsetGet($this->iteratorCurrentlyAtIndex);
    }
    public function key(){
        return $this->iteratorCurrentlyAtIndex;
    }
    public function next(){
        $this->iteratorCurrentlyAtIndex++;
    }
    public function rewind(){
        $this->iteratorCurrentlyAtIndex = 0;
    }
    public function valid(){
        return !empty($this->internalJson) && $this->iteratorCurrentlyAtIndex < $this->count;
    }
    public function offsetSet($index, $value){
        $this->offsetSetJSON($index, json_encode($value));
    }
    public function offsetUnset($index){
        $this->offsetSetJSON($index, '');
    }
    protected function setCountAndOffsetsByIndex(){
        if(!isset($this->internalJson[2])) {
            $this->count = 0;
            $this->offsetsByIndex = array();
            return;
        }
        $lastOffset = 1;
        $this->count = 1;
        $offsetsByIndex = array($lastOffset);
        $openHashPos = @strpos($this->internalJson, ',{', $lastOffset);
        $openArrayPos = @strpos($this->internalJson, ',[', $lastOffset);
        while (true) {
            $commaPos = @strpos($this->internalJson, ',', $lastOffset);
            if($commaPos === false) { break; }
            if($openHashPos && $openHashPos < $commaPos) {
                $closeHashPos = @strpos($this->internalJson, '},', $lastOffset);
                if($closeHashPos && $closeHashPos > $commaPos) {
                    $lastOffset = $closeHashPos+1;
                    $openHashPos = @strpos($this->internalJson, ',{', $lastOffset);
                    continue;
                }
            }
            if($openArrayPos && $openArrayPos < $commaPos) {
                $closeArrayPos = @strpos($this->internalJson, '],', $lastOffset);
                if($closeArrayPos && $closeArrayPos > $commaPos) {
                    $lastOffset = $closeArrayPos+1;
                    $openArrayPos = @strpos($this->internalJson, ',[', $lastOffset);
                    continue;
                }
            }
            $offsetsByIndex[] = $commaPos+1;
            $this->count++;
            $lastOffset = $commaPos+1;
        }
        $offsetsByIndex[] = @strrpos($this->internalJson, ']', $lastOffset)+1;
        $this->offsetsByIndex = SplFixedArray::fromArray($offsetsByIndex);
        $offsetsByIndex = null;
    }

    protected function offsetSetJSON($index, $valueAsJson){
        if($index === null) { //push
            $this->internalJson[strlen($this->internalJson)-1] = ',';
            $this->internalJson .= "$valueAsJson]";
            $this->count+=1;
            $this->offsetsByIndex->setSize($this->count+1);
            $this->offsetsByIndex[$this->count] = $this->offsetsByIndex[$this->count-1]
                + strlen($valueAsJson) + 1;
        } elseif($index < $this->count){ //editing
            $this->internalJson = substr($this->internalJson, 0, $this->offsetsByIndex[$index])
                . $valueAsJson . substr($this->internalJson, $this->offsetsByIndex[$index+1]-1);
            if(($diff = (strlen($valueAsJson)+1-$this->offsetsByIndex[$index+1]
                +$this->offsetsByIndex[$index])) !== 0)
                for ($i=$index+1; $i <= $this->count; $i++)
                    $this->offsetsByIndex[$i] += $diff;
        } else { //adding
            $this->offsetsByIndex->setSize($index+2);
            $this->internalJson[strlen($this->internalJson)-1] = ',';
            $this->internalJson .= str_repeat(',',$index-($this->count)) . "$valueAsJson]";
            for ($i=$this->count+1; $i < $index+1; $i++)
                $this->offsetsByIndex[$i] = $this->offsetsByIndex[$i-1]+1;
            $this->offsetsByIndex[$index+1] = $this->offsetsByIndex[$index]+1+strlen($valueAsJson);
            $this->count=$index+1;
        }
    }
}
