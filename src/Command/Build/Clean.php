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


class Clean extends \Qobo\Robo\AbstractCommand
{
    /**
     * Clean all after build
     *
     * @return bool true on success or false on failure
     */
    public function buildClean()
    {
        $result = $this->taskBuildClean()
            ->run();

        if (!$result->wasSuccessful()) {
            $this->exitError("Failed to run command");
        }

        $data = $result->getData();
        foreach ($data['data'] as $dir) {
            $this->say("Cleaned <info>$dir</info>");
        }
        return true;

    }
}
