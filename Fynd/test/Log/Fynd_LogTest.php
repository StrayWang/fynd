<?php
require_once '../../src/Fynd/Log.php';
require_once 'PHPUnit/Framework/TestCase.php';
/**
 * Fynd_Log test case.
 */
class Fynd_LogTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests Fynd_Log->__destruct()
     */
    public function test__destruct ()
    {
        $stream = fopen('php://memory', 'a');
        $writer = new Fynd_Log_StreamWriter($stream);
        $logger = new Fynd_Log('test__destruct', $writer);
        $logger->__destruct();
        try
        {
            $logger->log('log test', Fynd_Log::LOG_INFO);
            $this->fail();
        }
        catch (Exception $e)
        {
            $this->assertRegExp('/unable to write/i', $e->getMessage());
        }
    }
    /**
     * Tests Fynd_Log->log()
     */
    public function testLog ()
    {
        try
        {
            $stream = fopen('php://memory', 'a',false);
            $writer = new Fynd_Log_StreamWriter($stream);
            $logger = new Fynd_Log('testLog', $writer);
            $logger->log('log test', Fynd_log::LOG_INFO);
            rewind($stream);
            $log = stream_get_contents($stream);
            $this->assertRegExp('/INFO\t\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\t\w+\slog\stest/', $log);
            $logger->log('log test', Fynd_log::LOG_ERROR);
            rewind($stream);
            $log = stream_get_contents($stream);
            $this->assertRegExp('/ERROR\t\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\t\w+\slog\stest/', $log);
            $logger->log('log test', Fynd_log::LOG_WARN);
            rewind($stream);
            $log = stream_get_contents($stream);
            $this->assertRegExp('/WARN\t\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\t\w+\slog\stest/', $log);
        }
        catch (Exception $e)
        {
            $this->fail($e->getMessage());
        }
    }
    /**
     * Tests Fynd_Log->logError()
     */
    public function testLogError ()
    {
        try
        {
            $writer = new Fynd_Log_StreamWriter('php://memory');
            $logger = new Fynd_Log('testLogError', $writer);
            $logger->logError('log test');
            rewind($writer->getStream());
            $log = stream_get_contents($writer->getStream());
            $this->assertRegExp('/ERROR\t\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\t\w+\slog\stest/', $log);
        }
        catch (Exception $e)
        {
            $this->fail($e->getMessage());
        }
    }
    /**
     * Tests Fynd_Log->logInfo()
     */
    public function testLogInfo ()
    {
        try
        {
            $writer = new Fynd_Log_StreamWriter('php://memory');
            $logger = new Fynd_Log('testLogInfo', $writer);
            $logger->logInfo('log test');
            rewind($writer->getStream());
            $log = stream_get_contents($writer->getStream());
            $this->assertRegExp('/INFO\t\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\t\w+\slog\stest/', $log);
        }
        catch (Exception $e)
        {
            $this->fail($e->getMessage());
        }
    }
    /**
     * Tests Fynd_Log->logWarn()
     */
    public function testLogWarn ()
    {
        try
        {
            $writer = new Fynd_Log_StreamWriter('php://memory');
            $logger = new Fynd_Log('testLogWarn', $writer);
            $logger->logWarn('log test');
            rewind($writer->getStream());
            $log = stream_get_contents($writer->getStream());
            $this->assertRegExp('/WARN\t\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2}\t\w+\slog\stest/', $log);
        }
        catch (Exception $e)
        {
            $this->fail($e->getMessage());
        }
    }
}

