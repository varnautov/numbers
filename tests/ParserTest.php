<?php

namespace varnautov\numbers\test;

use varnautov\numbers\classes\ErrorException;
use varnautov\numbers\classes\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public $value;

    public function testRun()
    {
        $this->value = '111';
        $filename = 'input.txt';
        $needle = '1';
        $sort = SORT_ASC;
        $parser = $this->getMock(compact('filename', 'needle', 'sort'));
        $this->expectOutputString('111 3' . PHP_EOL);
        $parser->run();
    }

    public function testValidate()
    {
        $this->value = 'invalid';
        $filename = 'input.txt';
        $needle = '1';
        $sort = SORT_ASC;
        $parser = $this->getMock(compact('filename', 'needle', 'sort'));
        $this->expectException(ErrorException::class);
        $parser->run();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|Parser
     */
    protected function getMock($constructorArguments)
    {
        $m = $this->getMockBuilder(Parser::class)
            ->setConstructorArgs([$constructorArguments])
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->setMethods(['lineGenerator', 'lineGenerator2', 'getLine', 'rewind', 'fseek', 'fopen', 'fclose'])
            ->getMock();

        $m->method('lineGenerator')->willReturn($this->lineGenerator());
        $m->method('lineGenerator2')->willReturn($this->lineGenerator());
        $m->method('getLine')->willReturn($this->value);
        return $m;
    }

    protected function lineGenerator()
    {
        yield $this->value;
    }
}
