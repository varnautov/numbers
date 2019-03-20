<?php

namespace varnautov\numbers\classes;

use varnautov\numbers\interfaces\RunnableInterface;

/**
 * @todo add tests
 * @todo analyze replacement for \SplQueue for better performance
 */
class Parser implements RunnableInterface
{
    /**
     * @var string
     */
    public $filename;

    /**
     * @var string
     */
    public $needle;

    /**
     * @var int
     */
    public $sort;

    /**
     * @var int
     */
    public $lineLength;

    /**
     * @var int
     */
    protected $range;

    /**
     * @var array
     */
    protected $counters;

    /**
     * @var resource
     */
    protected $fh;

    /**
     * Parser constructor
     * @param string $filename
     * @param string $needle
     * @param int $sort
     */
    public function __construct(string $filename, string $needle, int $sort)
    {
        $this->filename = $filename;
        $this->needle = $needle;
        $this->sort = $sort;
        $this->lineLength = strlen(''.PHP_INT_MIN);
    }

    protected function validate($n, int $l)
    {
        if (!preg_match('~^-?\d+$~', $n)) {
            throw new ErrorException(sprintf('File contains non-integer value at line %d', $l));
        }
    }

    /**
     * @param string $line
     * @return int
     */
    protected function calculateCount(string $line)
    {
        return substr_count($line, $this->needle);
    }

    protected function findRange()
    {
        $l = 0;
        rewind($this->fh);
        while (($line = stream_get_line($this->fh, $this->lineLength, PHP_EOL)) !== false) {
            $this->validate($line, ++$l);
            $result = $this->calculateCount($line);
            if ($result > $this->range) {
                $this->range = $result;
            }
        }
        $this->range++;
    }

    protected function fillCounters()
    {
        // init
        $this->counters = array_fill(0, $this->range, null);
        for ($i = 0; $i < $this->range; $i++) {
            $this->counters[$i] = new \SplQueue();
        }
        // fill
        $offset = 0;
        rewind($this->fh);
        while (($line = stream_get_line($this->fh, $this->lineLength, PHP_EOL)) !== false) {
            $result = $this->calculateCount($line);
            $this->counters[$result]->enqueue($offset);
            $offset += strlen($line) + 1;
        }
    }

    protected function getSorted()
    {
        if ($this->sort === SORT_ASC) {
            for ($i=0; $i < $this->range; $i++) {
                $this->getSortedPart($i);
            }
        } else {
            for ($i = $this->range - 1; $i >= 0; $i--) {
                $this->getSortedPart($i);
            }
        }
    }

    protected function getSortedPart($i)
    {
        $q = $this->counters[$i];
        while (!$q->isEmpty()) {
            $offset = $q->dequeue();
            fseek($this->fh, $offset);
            if (($line = stream_get_line($this->fh, $this->lineLength, PHP_EOL)) !== false) {
                echo $line, ' ',  $i, PHP_EOL;
            }
        }
    }


    /**
     * @inheritdoc
     */
    public function run(): bool
    {
        $this->range = 0;
        $this->fh = fopen($this->filename, "r");
        $this->findRange();
        $this->fillCounters();
        $this->getSorted();
        fclose($this->fh);
        return true;
    }
}
