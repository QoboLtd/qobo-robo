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
namespace Qobo\Robo\Task\Build;

use Robo\Result;
use Symfony\Component\Filesystem\Filesystem as Filesystem;

class Clean extends Base
{
    /**
     * Clean build environment
     */
    public function run()
    {
        $dirs = [];
        foreach ($this->tasks as $task) {
            foreach ($this->dirKeys as $key) {
                if (isset($task[$key]) && !empty($task[$key])) {
                    $dirs []= $task[$key];
                }
            }
        }
        $dirs = array_unique($dirs);

        foreach ($dirs as $dir) {
            if (preg_match('/^\/[^\/]*$/', $dir)) {
                return Result::error($this, "Attempt to delete system dir '$dir'");
                die;
            }
        }

        $fs = new Filesystem();
        try {
            $fs->remove($dirs);
            $fs->mkdir($dirs);
        } catch (\Exception $e) {
            return Result::fromException($this, $e);
        }

        return Result::success($this, "All cleaned up", ['data' => $dirs]);
    }


}

