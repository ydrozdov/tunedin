<?php
namespace TN\Utility;

use TN\Exception\FtpConnectionException;
use TN\Exception\FtpLoginException;
use TN\Exception\FtpReadException;

/**
 * FTP wrapper class
 * @author yuriy
 *
 */
class FtpWrapper
{
    /**
     * 
     * @var resource
     */
    protected $conn;
    
    /**
     * Class constructor
     * 
     * @param string $server
     * @param string $user
     * @param string $pass
     * @param int $port
     * @param int $timeout
     * @throws FtpConnectionException
     */
    public function __construct($server, $user='anonymous', $pass='', $port=21, $timeout=90)
    {
        $this->conn = ftp_connect($server, $port, $timeout);
        if (!$this->conn) {
            throw new FtpConnectionException("Can't connect to " . $server);
        }
        
        $this->login($user, $pass);
    }
    
    /**
     * Class destructor
     */
    public function __destruct()
    {
        $this->close();
    }
    
    /**
     * Closes the given link identifier and releases the resource.
     */
    public function close()
    {
        if (is_resource($this->conn)) {
            ftp_close($this->conn);
        }
    }
    
    /**
     * Logs in to the given FTP stream.
     * 
     * @param string $user
     * @param string $pass
     * @throws FtpLoginException
     */
    public function login($user, $pass)
    {
        if (!ftp_login($this->conn, $user, $pass)) {
            throw new FtpLoginException('Login failed with ' . $user . ':' . $pass);
        }
    }
    
    /**
     * Retrieves a remote file from the FTP server, and saves it into a local file.
     * 
     * @param string $local
     * @param string $remote
     * @param int $mode
     * @throws FtpReadException
     */
    public function get($local, $remote, $mode=FTP_BINARY)
    {
        if (!ftp_get($this->conn, $local, $remote, $mode)) {
            throw new FtpReadException("Can't retrieve remote file " . $remote);
        }
    }
}