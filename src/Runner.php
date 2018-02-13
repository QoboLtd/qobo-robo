<?php
/**
 * Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Qobo\Robo;

use Robo\Runner as RoboRunner;

/**
 * Robo Runner to allow custom error handler
 */
class Runner extends RoboRunner
{
    private $lastErrno = null;

    /**
     * Custom error handler that will throw an exception on any errors
     */
    public function handleError()
    {
        // get error info
        list ($errno, $message, $file, $line) = func_get_args();

        // construct error message
        $msg = "ERROR ($errno): $message";
        if ($line !== null) {
            $file = "$file:$line";
        }

        if ($file !== null) {
            $msg .= " [$file]";
        }

        $this->lastErrno = $errno;

        // throw the exception
        throw new \RuntimeException($msg, $errno);
    }

    public function installRoboHandlers()
    {
        register_shutdown_function(array($this, 'shutdown'));

        if (PHP_MAJOR_VERSION < 7) {
            set_error_handler(array($this, 'handleError'), E_ALL & ~E_STRICT);
        } else {
            set_error_handler(array($this, 'handleError'), E_ALL);
        }
    }

    public function shutdown()
    {
        exit($this->lastErrno);
    }

    /**
     * @param string $selfUpdateRepository
     *
     * Have to have this here as it is not properly inherited from parent somehow
     */
    public function setSelfUpdateRepository($selfUpdateRepository)
    {
        $this->selfUpdateRepository = $selfUpdateRepository;
    }
}

