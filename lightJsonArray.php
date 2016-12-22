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
        $internalJsonLength = strlen($this->internalJson);
        for ($i=0; $i < $internalJsonLength; $i++){
            if($this->internalJson[$i] === '['){
                $offset = $i+1;
                $offsetsByIndex = array($offset);
                break;
            }
        }
        if($i === $internalJsonLength){
            $this->count = 0;
            $this->offsetsByIndex = new \SplFixedArray();
            return;
        }
        for ($limit=$i=$internalJsonLength-1; $i>$offset; $i--){
            if($this->internalJson[$i] === ']'){
                $limit = $i;
                break;
            }
        }
        if($i === $internalJsonLength || $limit === $offset){
            $this->count = 0;
            $this->offsetsByIndex = new \SplFixedArray();
            return;
        }
        $openedArrays = 0;
        $openedHashes = 0;
        if(strpos($this->internalJson, '\\"', $offset)){
            for ($i=$offset; $i<$limit; $i++) {
                switch ($this->internalJson[$i]) {
                    case ',':
                        if($openedArrays === 0 && $openedHashes === 0)
                            $offsetsByIndex[] = $i+1;
                        break;
                    case '"':
                        if($this->internalJson[$i-1] !== '\\')
                            do {
                                $newPos = strpos($this->internalJson, '"', $i+1);
                                if($newPos) { $i = $newPos; } else { break; }
                            } while ($this->internalJson[$i-1] === '\\');
                        break;
                    case '[': $openedArrays++; break;
                    case ']': $openedArrays--; break;
                    case '{': $openedHashes++; break;
                    case '}': $openedHashes--;
                }
            }
        } elseif(strpos($this->internalJson, '"', $offset)){
            for ($i=$offset; $i<$limit; $i++) {
                switch ($this->internalJson[$i]) {
                    case ',':
                        if($openedArrays === 0 && $openedHashes === 0)
                            $offsetsByIndex[] = $i+1;
                        break;
                    case '"':
                        $newPos = strpos($this->internalJson, '"', $i+1);
                        if($newPos) { $i = $newPos; }
                        break;
                    case '[': $openedArrays++; break;
                    case ']': $openedArrays--; break;
                    case '{': $openedHashes++; break;
                    case '}': $openedHashes--;
                }
            }
        } else {
            for ($i=$offset; $i<$limit; $i++) {
                switch ($this->internalJson[$i]) {
                    case ',':
                        $offsetsByIndex[] = $i+1;
                        break;
                    case '[':
                        $newPos = strpos($this->internalJson, ']', $i+1);
                        if($newPos) { $i = $newPos; }
                }
            }
        }
        $this->count = count($offsetsByIndex);
        $offsetsByIndex[] = $limit+1;
        $this->offsetsByIndex = \SplFixedArray::fromArray($offsetsByIndex);
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
