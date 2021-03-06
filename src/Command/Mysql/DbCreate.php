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
namespace Qobo\Robo\Command\Mysql;

use Qobo\Robo\AbstractCommand;
use Qobo\Robo\Formatter\PropertyList;

class DbCreate extends AbstractCommand
{
    /**
     * Create mysql database
     *
     * @param string $db Database name
     * @param string $user MySQL user to bind with
     * @param string $pass (Optional) MySQL user password
     * @param string $host (Optional) MySQL server host
     * @param string $port (Optional) MySQL server port
     * @option string $format Output format (table, list, csv, json, xml)
     * @option string $fields Limit output to given fields, comma-separated
     *
     * @return bool
     */
    public function mysqlDbCreate(
        $db,
        $user = 'root',
        $pass = null,
        $host = null,
        $port = null,
        $opts = ['format' => 'table', 'fields' => '']
    ) {
        $result = $this->taskMysqlDbCreate()
            ->db($db)
            ->user($user)
            ->pass($pass)
            ->host($host)
            ->port($port)
            ->hide($pass)
            ->run();

        if (!$result->wasSuccessful()) {
            $this->exitError("Failed to run command");
        }

        return true;
    }
}
