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
namespace Qobo\Robo\Command\Template;

use Qobo\Robo\AbstractCommand;
use Qobo\Robo\Formatter\PropertyList;

class Process extends AbstractCommand
{
    /**
     * Process given template with tokens from environment variables
     *
     * @param string $src Path to template
     * @param string $dst Path to final file
     * @option string $format Output format (table, list, csv, json, xml)
     * @option string $fields Limit output to given fields, comma-separated
     *
     * @return bool true on success, false on failure
     */
    public function templateProcess($src, $dst, $opts = ['format' => 'table', 'fields' => ''])
    {
        $result = $this->taskDotenvFileRead()
            ->path('.env')
            ->run();
        if (!$result->wasSuccessful()) {
            $this->exitError("Failed to run command");
        }

        $data = $result->getData();
        if (!isset($data['data'])) {
            $this->exitError("Failed to run command");
        }

        $result = $this->taskTemplateProcess()
            ->src($src)
            ->dst($dst)
            ->wrap('%%')
            ->tokens($data['data'])
            ->run();

        if (!$result->wasSuccessful()) {
            $this->exitError("Failed to run command");
        }

        return true;
    }
}
