<?php
namespace TN\Unit\Utility;

use TN\Utility\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function testCanCreateInstance()
    {
        $model = new Parser();
        $this->assertInstanceOf('TN\Utility\Parser', $model);
    }
    
    /**
     * @dataProvider provider
     */
    public function testParseReturnsCorrectArray(array $input, array $expected)
    {
        $model = new Parser();
        $result = $model->parse($input);
        
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        
        $resObj = array_pop($result);
        $expObj = array_pop($expected);
        
        $this->assertObjectHasAttribute('lineNum', $resObj);
        $this->assertObjectHasAttribute('showTitle', $resObj);
        $this->assertObjectHasAttribute('showYear', $resObj);
        $this->assertObjectHasAttribute('partYear', $resObj);
        $this->assertEquals($expObj, $resObj);
    }
    
    public function provider()
    {
        $input1 = array(
            '1   "#1 Single" (2006) {Cats and Dogs (#1.4)}               2006'
        );
        
        $expObj1 = new \stdClass();
        $expObj1->showTitle = '#1 Single';
        $expObj1->lineNum = 1;
        $expObj1->showYear = '2006';
        $expObj1->partTitle = 'Cats and Dogs';
        $expObj1->partSeasonNumber = '1';
        $expObj1->partEpisodeNumber = '4';
        $expObj1->partYear = '2006';
        
        $expected1 = array(
            $expObj1
        );
        
        return array(
            array($input1, $expected1)
        );
    }
}