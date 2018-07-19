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
namespace Qobo\Robo\Task\Mysql;

use Robo\Result;
use Qobo\Robo\Utility\Template;

/**
 * Export Mysql data class
 */
class Export extends \Qobo\Robo\AbstractCmdTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'cmd'   => 'mysqldump %%HOST%% %%PORT%% %%USER%% %%PASS%% %%DB%% > %%FILE%%',
        'path'  => ['./'],
        'batch' => false,
        'user'  => 'root',
        'file'  => 'etc/mysql.sql',
        'pass'  => null,
        'host'  => null,
        'port'  => null,
        'db'    => null
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredData = [
        'cmd',
        'user',
        'file'
    ];

    /**
     * {@inheritdoc}
     */
    protected $tokenKeys = [
        ['query', '-e '],
        ['host',  '-h '],
        ['port',  '-P '],
        ['user',  '-u '],
        ['pass',  '-p' ],
        'db',
        'file'
    ];

    /**
     * {@inhertidoc}
     */
    public function run()
    {
        // if password is empty, null it
        // so its not added to cmd during parsing
        if (empty($this->data['pass'])) {
            $this->data['pass'] = null;
        }

        return parent::run();
    }
}
