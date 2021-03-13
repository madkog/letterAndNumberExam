<?php

$inputHandler = new CommandLineInputHandler();
$stringLetterCounter = new StringLetterCounter();
$nextSmallestNumber = new NextSmallestNumber();

// If using command line
if(isset($argv)){
    $params = $inputHandler->getParams($argv);
} else { // If running from online php script editor
    $params = ['This is a test sentence.'];
    // params = ['1072'];
    // params = ['1027'];
}

$spacer = (count($params) > 1) ? " " : "";
foreach ($params as $param) {
    $output = (is_numeric($param)) ? $nextSmallestNumber->nextSmallestNumber($param) : $stringLetterCounter->countLetters($param);
    
    // Note did not use "new line due to online editor not being compliant. Used two spaces instead. 
    echo '"' . $output  . '"' . $spacer;
}

class CommandLineInputHandler {
    function __construct() { }
    public function getParams($argv) {
        array_shift($argv);
        return $argv;
    }
}

class StringLetterCounter {
    private $letterCounts = array();

    function __construct() {}

    public function countLetters($param) {
        $this->addAlphaCharacterToArray(strtolower($param));
        $output = $this->stringOutputOfCount($this->letterCounts);
        $this->letterCounts = array();
        return $output;
    }

    private function addAlphaCharacterToArray($param){
        $character = substr($param, 0, 1);

        if (preg_match('/[a-zA-Z]/', $character)) {
            $this->letterCounts[$character] = (isset($this->letterCounts[$character])) ? $this->letterCounts[$character] + 1 : 1;
        }
        
        if (strlen($param) !== 0) {
            $this->addAlphaCharacterToArray(substr($param, 1));
        }
    }

    private function stringOutputOfCount() {
        $formattedString = '';
        $outputArray = $this->letterCounts;
        ksort($outputArray);

        foreach($outputArray as $letter =>  $count) {
            $formattedString .= $count . $letter;
        }

        return $formattedString;
    } 
}

class NextSmallestNumber {
    private $outputText = 'nextSmaller';

    function __construct() {}

    public function nextSmallestNumber($param) {
        $numbers = str_split((string)$param);
        
        $nextCombinationArray = $this->nextCombination($numbers);

        if ($nextCombinationArray[0] == '0') {
            $nextCombination = "-1";
        } else {
            $nextCombination = implode('', $this->nextCombination($numbers));

            if($nextCombination === $param) {
                $nextCombination = "-1";
            }
        }
        
        return $this->stringOutputOfNumber($nextCombination, $param);
    }

    private function NextCombination($numbers, $unitAdjustment = 0) {
        $unitsCount = (count($numbers) - 1);
        $unitAdjustment = 0;
       
        $sharedNumbers = array();
        foreach($numbers as $number) {
            $lastDigit =  $numbers[$unitsCount - $unitAdjustment];
            $sharedNumbers[] = $lastDigit;

            $counter = 1;
            foreach ($sharedNumbers as $number) {
                if ($lastDigit > $number) {
                    $swapKey = array_search($number, array_reverse($numbers, true));

                    $numbers[$unitsCount - $unitAdjustment] = $number;
                    $numbers[$swapKey] = $lastDigit;
                    
                    $newArray = $this->sortFromPosition($numbers, ($unitsCount - $unitAdjustment));
    
                    return $newArray;
                }
                $counter++;
            }
            
            $unitAdjustment++;
        }
        return $numbers;
    }    

    private function sortFromPosition($numbers, $from) {
        $startArray = array();
        $endingArray = array(); 
        for ($x = 0; $x <= (count($numbers) - 1); $x++) {
            if ($x <= $from) {
                $startArray[] = $numbers[$x];
            } else {
                $endingArray[] = $numbers[$x];
            }
        }

        rsort($endingArray);

        return array_merge($startArray, $endingArray);
    }

    private function stringOutputOfNumber($number, $from) {
        $formattedString = $this->outputText . '('. $from . ') == ' . $number;
        
        return $formattedString;
    } 
}
