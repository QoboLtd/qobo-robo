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

/**
 * Base command class for Qobo Robo.li
 *
 * @see http://robo.li/
 * @see https://qobo.biz/
 */
namespace Qobo\Robo;

use Robo\Common\ConfigAwareTrait as configTrait;
use Robo\Tasks;
use RuntimeException;

abstract class AbstractCommand extends Tasks
{
    use configTrait;

    /**
     * @var const $MSG_NO_DELETE Common message to decline delete action unless forces to
     */
    const MSG_NO_DELETE = "Won't delete anything unless you force me with '--force' option";

    /**
     * @var string $taskRegex Regex pattern to match method name and extract task collection
     *                        directory by first match and task class by second match
     */
    protected $taskRegex = '/^task([A-Z]+.*?)([A-Z]+.*)$/';

    /**
     * @var string $taskDir path to Tasks dir relative to our namespace
     */
    protected $taskDir = 'Task';

    /**
     * Magic __call that tries to find and execute a correct task based
     * on called method name that must start with 'task'
     *
     * @param string $method Method name that was called
     * @param array $args Arguments that were passed to the method
     *
     * @return mixed
     */
    public function __call($method, $args = null)
    {
        if (preg_match($this->taskRegex, $method, $matches)) {
            $className = __NAMESPACE__ . "\\" . $this->taskDir . "\\" . $matches[1] . "\\" . $matches[2];
            if (!class_exists($className)) {
                throw new RuntimeException("Failed to find class '$className' for '$method' task");
            }
            return $this->task($className, $args);
        }
        throw new RuntimeException("Called to undefined method '$method' of '" . get_called_class() . "'");
    }

    /**
     * Terminate command with proper error code by throwing an exception
     *
     * @param string $message Error message
     * @param int $errorCode Error code for exit status
     */
    protected function exitError($message, $errorCode = 1)
    {
        throw new RuntimeException($message, $errorCode);
    }
}
