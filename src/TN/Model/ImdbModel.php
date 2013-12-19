<?php
namespace TN\Model;

/**
 * Class for IMDB Model
 * @author yuriy
 *
 */
class ImdbModel
{
    /**
     * The file
     * @var string
     */
    protected $dbFile;
    /**
     * Limits the request to requested number of results.
     * @var int
     */
    protected $limit;
    /**
     * Results offset
     * @var int
     */
    protected $offset;
    /**
     * Indicates sorting of the result by requested field
     * @var string
     */
    protected $sort;
    /**
     * When set, only movies/shows with at least partially matching titles are return.
     * @var string
     */
    protected $filterTitle;
    /**
     * Total number of result records
     * @var int
     */
    protected $total;
    
    /**
     * Number Of Returned Results
     * @var int
     */
    protected $numberOfReturnedResults;
    
    /**
     * Parser instance
     * @var TN\Utility\Parser
     */
    protected $parser;
    
    /**
     * Class constructor
     * @param string $dbFile
     * @param \TN\Utility\Parser $parser
     */
    public function __construct($dbFile, \TN\Utility\Parser $parser)
    {
        $this->setDbFile($dbFile);
        $this->setParser($parser);
        $this->total = false;
    }
    
    /**
     * Retrurns shows data
     */
    public function getShows()
    {
        $output = shell_exec(
            $this->getBaseCommand() .
            $this->getFilterCommand() .
            $this->getSortCommand() . 
            $this->getLimitCommand()
        );
        
        $result = array();
        $output = explode("\n", $output);
        array_pop($output);
        $output = array_map("ltrim", $output);
        $result = $this->parser->parse($output);
        $this->setNumberOfReturnedResults(count($result));
        return $result;
    }
    
    /**
     * Deletes a specific line from a file
     * @param int $lineNumber
     */
    public function delete($lineNumber)
    {
        exec(
            "sed -i '" . escapeshellarg($lineNumber) . "d' " . 
            escapeshellarg($this->dbFile)
        );
    }
    
    /**
     * Modifies a specific line in a file
     * @param int $lineNumber
     * @param string $title
     */
    public function patch($lineNumber, $title)
    {
        $line = shell_exec("sed -n '" . intval($lineNumber) . "p' " . escapeshellarg($this->dbFile));
        $parsedLine = $this->parser->parse(array($line));
        $oldTitle = $parsedLine[0]->showTitle;
        
        $escapedCommand = escapeshellcmd(
            "sed -i " . intval($lineNumber) .
            "'i'\"s/" . escapeshellarg($oldTitle) . "/" . escapeshellarg($title) . "/\" " . 
            escapeshellarg($this->dbFile)
        );
        
        exec($escapedCommand);
    }
    
    /**
     * Return total lines number
     * @return number
     */
    public function getTotal()
    {
        if (false === $this->total) {
            $output = shell_exec(
                'less ' . escapeshellarg($this->dbFile) . 
                $this->getFilterCommand() . ' | wc -l'
            );
            
            $total = intval($output);
        }
        
        return $total;
    }
    
    /**
     * Setter
     * @param string $dbFile
     */
    public function setDbFile($dbFile)
    {
        $this->dbFile = $dbFile;
        return $this;
    }
    
    /**
     * Setter
     * @param \TN\Utility\Parser $parser
     * @return \TN\Model\ImdbModel
     */
    public function setParser(\TN\Utility\Parser $parser)
    {
        $this->parser = $parser;
        return $this;
    }
    
    /**
     * Setter
     * @param int $limit
     * @return \TN\Model\ImdbModel
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }
    
    /**
     * Getter
     */
    public function getLimit()
    {
        return $this->limit;
    }
    
    /**
     * Setter
     * @param int $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        
        return $this;
    }
    
    /**
     * Getter
     */
    public function getOffset()
    {
        return $this->offset;
    }
    
    /**
     * Setter
     * @param string $sort
     * @return \TN\Model\ImdbModel
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
        return $this;
    }
    
    /**
     * Setter
     * @param string $filterTitle
     * @return \TN\Model\ImdbModel
     */
    public function setFilterTitle($filterTitle)
    {
        $this->filterTitle = $filterTitle;
        return $this;
    }
    
    /**
     * Setter
     * @param int $num
     * @return \TN\Model\ImdbModel
     */
    public function setNumberOfReturnedResults($num)
    {
        $this->numberOfReturnedResults = $num;
        return $this;
    }
    
    /**
     * Getter
     */
    public function getNumberOfReturnedResults()
    {
        return $this->numberOfReturnedResults;
    }
    
    /**
     * Returns basic command syntax
     */
    protected function getBaseCommand()
    {
        return 'nl -s " " -b a ' . escapeshellarg($this->dbFile);
    }
    
    /**
     * Returns filter command syntax
     */
    protected function getFilterCommand()
    {
        $command = '';
        if ($this->filterTitle) {
            $command = ' | grep -i ' . escapeshellarg($this->filterTitle) . ' ';
        }
        
        return $command;
    }
    
    /**
     * Returns sort command syntax
     */
    protected function getSortCommand()
    {
        $command = '';
        if ($this->sort) {
            $command = ' | sort -k' . $this->getSortColumn();
        }
        
        return $command;
    }
    
    /**
     * Returns sort column
     */
    protected function getSortColumn()
    {
        $sortColumns = array(
            'name' => 2,
            'year' => 3
        );

        return isset($sortColumns[$this->sort]) ? $sortColumns[$this->sort] : 2;
    }
    
    /**
     * Returns limit command syntax
     */
    protected function getLimitCommand()
    {
        return " | sed -n '" . ((0 == intval($this->offset)) ? 1 : intval($this->offset)) . 
            "," . intval($this->limit) . "p' ";
    }

}