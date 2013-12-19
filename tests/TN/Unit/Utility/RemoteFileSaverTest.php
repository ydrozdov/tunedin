<?php
namespace TN\Unit\Utility;

use TN\Utility\RemoteFileSaver;

class RemoteFileSaverTest extends \PHPUnit_Framework_TestCase
{
    public function testCanCreateInstance()
    {
        $ftp = $this->getMockBuilder('\TN\Utility\FtpWrapper')
            ->disableOriginalConstructor()
            ->getMock();
        
        $decompressor = $this->getMockBuilder('\TN\Utility\Decompressor')
            ->setConstructorArgs(array('in', 'out'))
            ->getMock();
        
        $model = new RemoteFileSaver($ftp, $decompressor, 'local', 'remote');
        
        $this->assertInstanceOf('\TN\Utility\RemoteFileSaver', $model);
    }
    
    public function testSaveCallsFtpAndDecompressorMethods()
    {
        $ftp = $this->getMockBuilder('\TN\Utility\FtpWrapper')
            ->disableOriginalConstructor()
            ->getMock();
        
        $decompressor = $this->getMockBuilder('\TN\Utility\Decompressor')
            ->setConstructorArgs(array('in', 'out'))
            ->setMethods(array('decompress'))
            ->getMock();
        
        $decompressor->expects($this->once())->method('decompress');
        
        $modelMock = $this->getMockBuilder('\TN\Utility\RemoteFileSaver')
            ->setConstructorArgs(array($ftp, $decompressor, 'in', 'out'))
            ->setMethods(array('removeUselessLines'))
            ->getMock();
        
        $modelMock->expects($this->once())->method('removeUselessLines');
        
        $modelMock->save();
    }
}