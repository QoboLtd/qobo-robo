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
namespace Qobo\Robo\Command\Project;

use Qobo\Robo\AbstractCommand;
use Qobo\Robo\Formatter\PropertyList;

class DotenvReload extends AbstractCommand
{
    /**
     * Reload environment from given dotenv file
     *
     * @param string $envPath Path to dotenv file
     * @option string $format Output format (table, list, csv, json, xml)
     * @option string $fields Limit output to given fields, comma-separated
     *
     * @return PropertyList result
     */
    public function projectDotenvReload($envPath = '.env', $opts = ['format' => 'table', 'fields' => ''])
    {
        // Reload
        $result = $this->taskDotenvReload()
            ->path($envPath)
            ->run();

        if (!$result->wasSuccessful()) {
            $this->exitError("Failed to run command");
        }

        return new PropertyList($result->getData());
    }
}
