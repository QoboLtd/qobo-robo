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


class All extends \Qobo\Robo\AbstractCommand
{
    /**
     * Run all build commands
     *
     * @return bool true on success or false on failure
     */
    public function buildAll()
    {
        $result = $this->taskBuildAll()
            ->run();

        if (!$result->wasSuccessful()) {
            $this->exitError("Failed to run command");
        }

        $data = $result->getData();
        unset($data['time']);
        foreach ($data as $cmd) {
            $this->say("Command <info>" . $cmd['cmd'] . "</info>");
            if (!isset($cmd['data'][0]['output']) || empty($cmd['data'][0]['output'])) {
                continue;
            }
            foreach ($cmd['data'][0]['output'] as $output) {
                $this->say($output);
            }
        }

        return true;
    }
}
