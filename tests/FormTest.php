<?php

namespace varnautov\numbers\test;

use varnautov\numbers\classes\ErrorException;
use varnautov\numbers\classes\Form;
use varnautov\numbers\classes\Parser;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
    public function testValidateNoFilename()
    {
        $form = $this->getMock(true);
        $form->filename = null;
        $form->needle = '1';
        $form->sort = 'asc';
        $form->parser = $this->createMock(Parser::class);
        $this->expectException(ErrorException::class);
        $form->validate();
    }

    public function testValidateInvalidFilename()
    {
        $form = $this->getMock(false);
        $form->filename = 'invalid';
        $form->needle = '1';
        $form->sort = 'asc';
        $form->parser = $this->createMock(Parser::class);
        $this->expectException(ErrorException::class);
        $form->validate();
    }

    public function testValidateNoNeedle()
    {
        $form = $this->getMock(true);
        $form->filename = 'input.txt';
        $form->needle = null;
        $form->sort = 'asc';
        $form->parser = $this->createMock(Parser::class);
        $this->expectException(ErrorException::class);
        $form->Validate();
    }

    public function testValidateInvalidNeedle()
    {
        $form = $this->getMock(true);
        $form->filename = 'input.txt';
        $form->needle = 'x';
        $form->sort = 'asc';
        $form->parser = $this->createMock(Parser::class);
        $this->expectException(ErrorException::class);
        $form->validate();
    }

    public function testValidateInvalidSort()
    {
        $form = $this->getMock(true);
        $form->filename = 'input.txt';
        $form->needle = 'x';
        $form->sort = 'invalid';
        $form->parser = $this->createMock(Parser::class);
        $this->expectException(ErrorException::class);
        $form->validate();
    }

    public function testValidateNoParser()
    {
        $form = $this->getMock(true);
        $form->filename = 'input.txt';
        $form->needle = '1';
        $form->sort = 'asc';
        $form->parser = null;
        $this->expectException(ErrorException::class);
        $form->validate();
    }

    public function testValidateInvalidParser()
    {
        $form = $this->getMock(true);
        $form->filename = 'input.txt';
        $form->needle = '1';
        $form->sort = 'asc';
        $form->parser = 'invalid';
        $this->expectException(ErrorException::class);
        $form->validate();
    }

    public function testRun()
    {
        $form = $this->getMock(true);
        $form->filename = 'input.txt';
        $form->needle = '1';
        $form->sort = 'asc';
        $form->parser = $this->createConfiguredMock(Parser::class, ['run' => true]);
        $this->assertTrue($form->run());
    }

    /**
     * @param bool $isFile
     * @return \PHPUnit\Framework\MockObject\MockObject|Form
     */
    public function getMock(bool $isFile)
    {
        $m = $this->createPartialMock(Form::class, ['isFile', 'run']);
        $m->method('isFile')->willReturn($isFile);
        $m->method('run')->willReturn(true);
        return $m;
    }
}
