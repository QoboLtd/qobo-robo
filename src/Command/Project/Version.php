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

class Version extends AbstractCommand
{
    /**
     * Get project version
     *
     * @option string $format Output format (table, list, csv, json, xml)
     * @option string $fields Limit output to given fields, comma-separated
     *
     * @return PropertyList result
     */
    public function projectVersion($opts = ['format' => 'table', 'fields' => ''])
    {
        $result = $this->taskDotenvReload()->run();

        $envVersion = getenv('GIT_BRANCH');
        if (!empty($envVersion)) {
            return new PropertyList(['version' => $envVersion]);
        }

        $result = $this->taskGitHash()->run();
        if ($result->wasSuccessful()) {
            return new PropertyList(['version' => $result->getData()['data'][0]['message'] ]);
        }

        return new PropertyList(['version' => 'Unknown']);
    }
}
