<?php
namespace TN\Unit\Utility;

use TN\Utility\Decompressor;

class DecompressorTest extends \PHPUnit_Framework_TestCase
{
    public function testCanCreateInstance()
    {
        $model = new Decompressor('somefile', 'outfile', 4096);
        $this->assertInstanceOf('\TN\Utility\Decompressor', $model);
    }
}