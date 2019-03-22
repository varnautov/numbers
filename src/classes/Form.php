<?php

namespace varnautov\numbers\classes;

use varnautov\numbers\interfaces\RunnableInterface;

class Form extends Component implements RunnableInterface
{
    /**
     * @var string
     */
    public $filename;

    /**
     * @var int
     */
    public $needle;

    /**
     * @var string
     */
    public $sort;

    /**
     * @var string
     */
    public $parser;

    /**
     * @var int
     */
    protected $sortMethod;

    /**
     * Validate.
     */
    public function validate()
    {
        // filename
        if (!$this->filename) {
            throw new ErrorException('Input file is not set.');
        } elseif (!$this->isFile()) {
            throw new ErrorException('Input is not a file.');
        }

        // needle
        if (!$this->needle) {
            throw new ErrorException('Needle is not set.');
        } elseif (!preg_match('~^\d{1}$~', $this->needle)) {
            throw new ErrorException('Needle is invalid.');
        }

        // sort
        if ($this->sort) {
            $this->sort = strtolower($this->sort);
            if (!in_array($this->sort, ['asc', 'desc'])) {
                throw new ErrorException('Invalid sort.');
            }
        }

        // parser
        if (!$this->parser) {
            throw new ErrorException('Parser is not set.');
        } elseif (!is_subclass_of($this->parser, RunnableInterface::class)) {
            throw new ErrorException('Parser is invalid.');
        }
    }

    /**
     * FOR TESTING PURPOSES ONLY
     * @return bool
     */
    public function isFile(): bool
    {
        return is_file($this->filename);
    }

    /**
     * @inheritdoc
     */
    public function run(): bool
    {
        $this->validate();
        $this->sortMethod = $this->sort === 'desc' ? SORT_DESC : SORT_ASC;
        $parser = new $this->parser($this->filename, $this->needle, $this->sortMethod);
        /** @var RunnableInterface $parser */
        return $parser->run();
    }
}
