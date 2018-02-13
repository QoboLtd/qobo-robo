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

/**
 * Create Mysql database
 */
class DbCreate extends BaseQuery
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'cmd'   => 'mysql %%HOST%% %%PORT%% %%USER%% %%PASS%% %%QUERY%%',
        'path'  => ['./'],
        'batch' => false,
        'user'  => 'root',
        'pass'  => null,
        'host'  => null,
        'port'  => null,
        'query' => null,
        'db'    => null
    ];


    /**
     * {@inheritdoc}
     */
    protected $query = "CREATE DATABASE IF NOT EXISTS %%DB%%";
}
