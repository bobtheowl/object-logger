<?php
namespace ObjectLogger\Laravel;

use Illuminate\Log\Writer as IlluminateWriter;
use ObjectLogger\ObjectLoggerTrait;

class Writer extends IlluminateWriter
{
    /**
     * Checks to see if the message uses ObjectLoggerTrait.
     *
     * @param  mixed  $message
     */
    protected function usesObjectLogger($message)
    {
        return (
            is_object($message) &&
            in_array(ObjectLoggerTrait::class, class_uses($message))
        );
    }

    /**
     * Log an emergency message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function emergency($message, array $context = [])
    {
        if ($this->usesObjectLogger($message)) {
            $message = $message->olGenerateMessage();
        }//end if
        return parent::emergency($message, $context);
    }
 
    /**
     * Log an alert message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function alert($message, array $context = [])
    {
        if ($this->usesObjectLogger($message)) {
            $message = $message->olGenerateMessage();
        }//end if
        return parent::alert($message, $context);
    }
 
    /**
     * Log a critical message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function critical($message, array $context = [])
    {
        if ($this->usesObjectLogger($message)) {
            $message = $message->olGenerateMessage();
        }//end if
        return parent::critical($message, $context);
    }
 
    /**
     * Log an error message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function error($message, array $context = [])
    {
        if ($this->usesObjectLogger($message)) {
            $message = $message->olGenerateMessage();
        }//end if
        return parent::error($message, $context);
    }
 
    /**
     * Log a warning message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function warning($message, array $context = [])
    {
        if ($this->usesObjectLogger($message)) {
            $message = $message->olGenerateMessage();
        }//end if
        return parent::warning($message, $context);
    }
 
    /**
     * Log a notice to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function notice($message, array $context = [])
    {
        if ($this->usesObjectLogger($message)) {
            $message = $message->olGenerateMessage();
        }//end if
        return parent::notice($message, $context);
    }
 
    /**
     * Log an informational message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function info($message, array $context = [])
    {
        if ($this->usesObjectLogger($message)) {
            $message = $message->olGenerateMessage();
        }//end if
        return parent::info($message, $context);
    }
 
    /**
     * Log a debug message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function debug($message, array $context = [])
    {
        if ($this->usesObjectLogger($message)) {
            $message = $message->olGenerateMessage();
        }//end if
        return parent::debug($message, $context);
    }
 
    /**
     * Log a message to the logs.
     *
     * @param  string  $level
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        if ($this->usesObjectLogger($message)) {
            $message = $message->olGenerateMessage();
        }//end if
        return parent::log($level, $message, $context);
    }
 
    /**
     * Dynamically pass log calls into the writer.
     *
     * @param  string  $level
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function write($level, $message, array $context = [])
    {
        if ($this->usesObjectLogger($message)) {
            $message = $message->olGenerateMessage();
        }//end if
        return parent::write($level, $message, $context);
    }
}//end class Writer

//end file Writer.php
