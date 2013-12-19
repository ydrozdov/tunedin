<?php
namespace TN\Unit\Model;

use TN\Model\ImdbModel;

class ImdbModelTest extends \PHPUnit_Framework_TestCase
{
    public function testCanCreateInstance()
    {
        $parserMock = $this->getMock('\TN\Utility\Parser');
        $model = new ImdbModel('dbFile', $parserMock);
        
        $this->assertInstanceOf('\TN\Model\ImdbModel', $model);
    }
}
