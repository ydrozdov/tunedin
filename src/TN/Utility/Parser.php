<?php
namespace TN\Utility;

/**
 * Class for IMDB structure parse
 * @author yuriy
 *
 */
class Parser
{
    /**
     * Parses array of lines
     * @param array $input
     */
    public function parse(array $input)
    {
        $result = array();
        foreach ($input as $line) {
            $lineObj = new \stdClass();
        
            $lineArray = explode(' ', $line, 2);
            $lineObj->lineNum = $lineArray[0];
            $line = $lineArray[1];
        
            $lineArray = explode('(', $line, 2);
            $lineObj->showTitle = trim(str_replace('"', '', $lineArray[0]));
            $line = $lineArray[1];
        
            $lineArray = explode(')', $line, 2);
            $lineObj->showYear = $lineArray[0];
            $line = $lineArray[1];
        
            if (false !== strpos($line, '{')) {
                $lineArray = explode('{', $line, 2);
                $line = $lineArray[1];
        
                $lineArray = explode('}', $line, 2);
                $lineObj->partTitle = $lineArray[0];
                $line = $lineArray[1];
        
                if (false !== strpos($lineObj->partTitle, '(#')) {
                    $lineArray = explode('(#', $lineObj->partTitle, 2);
                    $lineObj->partTitle = trim($lineArray[0]);
                    $lineObj->partSeasonNumber = $lineArray[1];
        
                    if (false !== strpos($lineObj->partSeasonNumber, '.')) {
                        $lineArray = explode('.', $lineObj->partSeasonNumber, 2);
                        $lineObj->partSeasonNumber = trim($lineArray[0]);
                        $lineObj->partEpisodeNumber = str_replace(")", "", $lineArray[1]);
                    }
                }
            }
        
            $lineObj->partYear = trim($line);
        
            $result[] = $lineObj;
        }
        
        return $result;
    }
}