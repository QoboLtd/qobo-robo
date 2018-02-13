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

use Robo\Result;

/**
 * Git current project changelog
 *
 * ```php
 * <?php
 * $this->taskProjectChangelog()
 * ->format('--reverse --no-merges --pretty=format:"* %<(72,trunc)%s (%ad, $an)" --date=short')
 * ->run();
 *
 * ?>
 * ```
 */
class Changelog extends \Qobo\Robo\AbstractCmdTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'cmd'   => 'git log --reverse --no-merges --pretty=format:"* %<(72,trunc)%s (%ad, %an)" --date=short',
        'path'  => ['./'],
        'batch' => false
    ];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->printInfo("Retrieving project changelog");
        $result = parent::run();
        if (!$result->wasSuccessful()) {
            return $result;
        }

        return Result::success($this, "Successfully retrieved project changelog", $this->data);
    }
}
