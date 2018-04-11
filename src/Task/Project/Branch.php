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
namespace Qobo\Robo\Task\Project;

use Qobo\Robo\AbstractCmdTask;
use Robo\Result;

/**
 * Current project branch
 *
 * ```php
 * <?php
 * $this->taskProjectBranch()
 * ->run();
 *
 * ?>
 * ```
 */
class Branch extends AbstractCmdTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'cmd'   => 'git rev-parse --abbref-ref HEAD',
        'path'  => ['./'],
        'batch' => false
    ];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->printInfo("Retrieving project branch");
        $result = parent::run();
        if (!$result->wasSuccessful()) {
            return $result;
        }

        return Result::success($this, "Successfully retrieved project branch", $this->data);
    }
}
