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
namespace Qobo\Robo\Task\Git;

use Qobo\Robo\AbstractCmdTask;
use Robo\Result;

/**
 * Git current project's git hash
 *
 * ```php
 * <?php
 * $this->taskGitHash()
 * ->run();
 *
 * ?>
 * ```
 */
class Hash extends AbstractCmdTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'cmd'   => 'git log -1 --pretty=format:"%h" -C %%PATH%%',
        'path'  => ['./'],
        'batch' => false
    ];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->printInfo("Retrieving repo git hash");
        $result = parent::run();
        if (!$result->wasSuccessful()) {
            return $result;
        }

        $data = $result->getData();
        if (!ctype_xdigit($data['data'][0]['message'])) {
            return Result::error($this, "Retrieved result is not a hash", $this->data);
        }

        return Result::success($this, "Successfully retrieved repo hash", $this->data);
    }
}
