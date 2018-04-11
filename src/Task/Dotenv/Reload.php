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
namespace Qobo\Robo\Task\Dotenv;

use Exception;
use Qobo\Robo\AbstractTask;
use Qobo\Robo\Utility\File;
use Qobo\Robo\Utility\Dotenv;
use Robo\Result;

/**
 * Reload environment from dotenv file
 *
 * ```php
 * <?php
 * $this->taskDotenvReload()
 * ->path('.env')
 * ->run();
 * ?>
 * ```
 */
class Reload extends AbstractTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'path'          => '.env',
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredData = [
        'path',
    ];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $result = parent::run();
        if (!$result->wasSuccessful()) {
            return $result;
        }

        $this->printInfo("Reloading environment from {path} dotenv file", $this->data);

        try {
            $content = File::readLines($this->data['path']);
            $dotenv = Dotenv::parse($content);
            $this->data['data'] = Dotenv::apply($dotenv, [], Dotenv::FLAG_REPLACE_DUPLICATES);
        } catch (Exception $e) {
            return Result::fromException($this, $e);
        }

        return Result::success($this, "Successfully reloaded environment", $this->data);
    }
}
