<?php
require_once '../../src/Fynd/Log/StreamWriter.php';
require_once 'PHPUnit/Framework/TestCase.php';
/**
 * Fynd_Log_StreamWriter test case.
 */
class Fynd_Log_StreamWriterTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests Fynd_Log_StreamWriter->__construct()
     */
    public function test__construct ()
    {
        $writer = new Fynd_Log_StreamWriter('php://memory');
        $this->assertType('Fynd_Log_StreamWriter', $writer);
        $writer = null;
        $stream = fopen('php://memory', 'a');
        $writer = new Fynd_Log_StreamWriter($stream);
        $this->assertType('Fynd_Log_StreamWriter', $writer);
        $writer = null;
    }
    public function test__construct_Fail ()
    {
        try
        {
            new Fynd_Log_StreamWriter('');
            $this->fail();
        }
        catch (Exception $e)
        {
            $this->assertRegExp('/can not open log stream/i', $e->getMessage());
        }
        try
        {
            $resource = xml_parser_create();
            new Fynd_Log_StreamWriter($resource);
            xml_parser_free($resource);
            $this->fail();
        }
        catch (Exception $e)
        {
            $this->assertRegExp('/not a stream/i', $e->getMessage(),$e->getMessage());
        }
    }
    /**
     * Tests Fynd_Log_StreamWriter->close()
     */
    public function testClose ()
    {
        try
        {
            $stream = fopen('php://memory', 'a');
            $writer = new Fynd_Log_StreamWriter($stream);
            $writer->close();
            $writer->write('write test');
            $this->fail();
        }
        catch (Exception $e)
        {
            $this->assertRegExp('/unable to write/i', $e->getMessage());
        }
    }
    /**
     * Tests Fynd_Log_StreamWriter->getStream()
     */
    public function testGetStream ()
    {
        $writer = new Fynd_Log_StreamWriter('php://memory');
        $stream = $writer->getStream();
        $this->assertTrue(is_resource($stream));
        $writer->close();
    }
    /**
     * Tests Fynd_Log_StreamWriter->write()
     */
    public function testWrite ()
    {
        $stream = fopen('php://memory', 'a');
        $writer = new Fynd_Log_StreamWriter($stream);
        $writer->write('write test');
        rewind($stream);
        $content = stream_get_contents($stream);
        fclose($stream);
        $this->assertContains('write test', $content);
    }
}

