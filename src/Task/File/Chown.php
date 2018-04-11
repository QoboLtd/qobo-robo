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
namespace Qobo\Robo\Task\File;

use Qobo\Robo\AbstractTask;
use Robo\Result;

/**
 * Change file/dir ownership
 *
 * ```php
 * <?php
 * $this->taskFileChown()
 * ->path(['somefile.txt', './somedir'])
 * ->user('nginx')
 * ->recursive(true)
 * ->run();
 *
 * ?>
 * ```
 */
class Chown extends AbstractTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'path'  => [],
        'user' => null,
        'recursive' => false,
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredData = [
        'path',
        'user'
    ];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $result = false;

        if (!is_array($this->data['path'])) {
            $this->data['path'] = [ $this->data['path'] ];
        }
        foreach ($this->data['path'] as $path) {
            $this->printInfo(
                "Changing user ownership on {path} to {user}",
                ['path' => $path, 'user' => $this->data['user']]
            );
            $result = static::chown($path, $this->data['user'], $this->data['recursive']);
        }

        if ($result) {
            return Result::success($this, "Successfully changed path's user ownership", $this->data);
        }
        return Result::error($this, "Failed to change path's user ownership");
    }

    public static function chown($path, $user, $recursive)
    {
        $path = realpath($path);

        $result = chown($path, $user);

        if (!$result || is_file($path) || !$recursive) {
            return $result;
        }

        $paths = glob("$path/*");
        foreach ($paths as $path) {
            $result = static::chown($path, $user, true);
            if (!$result) {
                return $result;
            }
        }
        return $result;
    }
}
