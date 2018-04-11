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

use Qobo\Robo\AbstractCmdTask;

class DbFindReplace extends AbstractCmdTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'cmd'   => './vendor/bin/srdb.cli.php -h %%HOST%% %%PORT%% -u %%USER%% -p %%PASS%% -n %%DB%% -s %%SEARCH%% -r %%REPLACE%%',
        'path'  => ['./'],
        'host'  => 'localhost',
        'user'  => 'root',
        'pass'  => '',
        'port'  => null,
        'db'    => null,
        'search'    => null,
        'replace'   => null,
        'batch' => false
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredData = [
        'cmd',
        'path',
        'host',
        'user',
        'pass',
        'db',
        'search',
        'replace',
        'batch'
    ];

    /**
     * {@inheritdoc}
     */
    protected $tokenKeys = [
        'host',
        'user',
        'pass',
        'db',
        'search',
        'replace',
        ['port', '--port ']
    ];
}
