<?php
namespace TN\Utility;

class Decompressor
{
    /**
     * The compressed file name.
     * @var string
     */
    protected $compressedFile;
    
    /**
     * The out file name.
     * @var string
     */
    protected $outFile;
    
    /**
     * Read buffer size.
     * @var int
     */
    protected $bufferSize;
    
    /**
     * Class constructor
     * 
     * @param string $compressedFile
     * @param string $outFile
     * @param int $bufferSize
     */
    public function __construct($compressedFile, $outFile, $bufferSize=4096)
    {
        $this->compressedFile = $compressedFile;
        $this->outFile = $outFile;
        $this->bufferSize = $bufferSize;
    }
    
    /**
     * Decompresses a file
     * @return decompressed file name
     */
    public function decompress()
    {
        $file = gzopen($this->compressedFile, 'rb');
        $outFile = new \SplFileObject($this->outFile, "w");
        // Keep repeating until the end of the input file
        while (!gzeof($file)) {
            // Read buffer-size bytes
            // Both fwrite and gzread and safe
            $outFile->fwrite(gzread($file, $this->bufferSize));
        }
        
        gzclose($file);
        
        return $this->outFile;
    }
}