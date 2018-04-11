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

use Qobo\Robo\Runner;
use Robo\Robo as RoboRobo;

/**
 * Extend Robo to overwrite run method and substitute our own Runner
 */
class Robo extends RoboRobo
{

    public static function run(
        $argv,
        $commandClasses,
        $appName = null,
        $appVersion = null,
        $output = null,
        $repository = null
    ) {
        // This line is the whole idea of the class
        $runner = new Runner($commandClasses);


        $runner->setSelfUpdateRepository($repository);
        $statusCode = $runner->execute($argv, $appName, $appVersion, $output);
        return $statusCode;
    }
}
