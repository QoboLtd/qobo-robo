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
namespace Qobo\Robo\Command\Build;


class PhpLoc extends \Qobo\Robo\AbstractCommand
{
    /**
     * Run PHP Loc analyzer and measuring tool
     *
     * @return true on success or false on failure
     */
    public function buildPhpLoc()
    {
        $result = $this->taskBuildPhpLoc()
            ->path(['./src', './tests'])
            ->run();

        if (!$result->wasSuccessful()) {
            $this->exitError("Failed to run command");
        }

        foreach ($result->getData()['data'][0]['output'] as $line) {
            $this->say($line);
        }

        return true;
    }
}
