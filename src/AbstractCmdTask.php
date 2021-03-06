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
namespace Qobo\Robo;

use Exception;
use InvalidArgumentException;
use Robo\Result;
use Robo\Exception\TaskException;
use Qobo\Robo\Utility\Template;

/**
 * Qobo base command task.
 */
abstract class AbstractCmdTask extends AbstractTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        // command to run
        'cmd'   => null,

        // array of paths agains which to run a command. If empty, command will not run (use './')
        'path'  => [],

        // whether to run a command agains each path separatly or in batch (path will be joined with ' ')
        'batch' => false,

        // path for any logs to be written to
        'logs'  => null,

        // path for any output files to be written to
        'out'   => null,
    ];

    /**
     * {@inhericdoc}
     */
    protected $requiredData = [
        'cmd',
        'path',
        'batch'
    ];

    /**
     * @var array $tokenKeys data keys to use as tokens in cmd
     */
    protected $tokenKeys = [
        'out',
        'logs'
    ];

    /**
     * @var array $hiddenOutput replace matching strings of output with stars
     */
    protected $hiddenOutput = [
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

        if (!is_array($this->data['path'])) {
            $this->data['path'] = [ $this->data['path'] ];
        }
        // validate our cmd and paths
        try {
            static::checkCmd($this->data['cmd']);
            foreach ($this->data['path'] as $path) {
                static::checkPath($path);
            }
        } catch (Exception $e) {
            return Result::fromException($this, $e);
        }

        // get a list of commands to run
        $cmds = $this->getCommands();

        $this->data['data'] = [];
        $data = [];
        foreach ($cmds as $cmd) {
            $output = $cmd;
            foreach ($this->hiddenOutput as $str) {
                $output = str_replace($str, '******', $output);
            }
            $this->printInfo("Running {cmd}", ['cmd' => $output]);
            $data = $this->runCmd($cmd);
            $this->data['data'] []= $data;

            // POSIX commands will exit with 0 on success
            // and 1 on failure
            if ($data['status'] && $this->stopOnFail) {
                return Result::error($this, "Last command failed to run", $this->data);
            }
        }

        return ($data['status'])
            ? Result::error($this, "Last command failed to run", $this->data)
            : Result::success($this, "Commands run successfully", $this->data);
    }

    /**
     * Add strings to hide from output
     */
    public function hide($str)
    {
        $this->hiddenOutput []= $str;

        return $this;
    }

    /**
     * Check if path is readable
     */
    public static function checkPath($path)
    {
        if (!is_string($path)) {
            throw new InvalidArgumentException(sprintf("String expected as path, got '%s' instead", gettype($path)));
        }
        if (!file_exists($path)) {
            throw new InvalidArgumentException(sprintf("'%s' does not exist", $path));
        }

        if (!is_dir($path)) {
            throw new InvalidArgumentException(sprintf("'%s' is not a directory", $path));
        }

        if (!is_readable($path)) {
            throw new InvalidArgumentException(sprintf("'%s' is not readable", $path));
        }

        return true;
    }

    /**
     * Check if cmd exists and is an executable file
     */
    public static function checkCmd($cmd)
    {
        // cut out the actual executable part only
        // and leave args away
        $cmdParts = preg_split("/\s+/", $cmd);
        $cmd = $cmdParts[0];

        // try to find a command if not absolute path is given
        if (!preg_match('/^\.?\/.*$/', $cmd)) {
            $retval = null;
            $ouput = [];
            $fullCmd = exec("which $cmd", $output, $retval);
            if ($retval) {
                throw new InvalidArgumentException(sprintf("Failed to find full path for '%s'", $cmd));
            }
            $cmd = trim($fullCmd);
        }

        if (!file_exists($cmd)) {
            throw new InvalidArgumentException(sprintf("'%s' does not exist", $cmd));
        }

        if (!is_file($cmd)) {
            throw new InvalidArgumentException(sprintf("'%s' is not a file", $cmd));
        }
        if (!is_executable($cmd)) {
            throw new InvalidArgumentException(sprintf("'%s' is not executable", $cmd));
        }

        return true;
    }

    protected function runCmd($cmd)
    {
        $output = null;
        $status = null;
        $data = [];
        $data['message'] = exec($cmd, $output, $status);

        $data['output'] = $output;
        $data['status'] = $status;

        return $data;
    }

    /**
     * Get commands
     */
    public function getCommands()
    {
        // generate basic tokens
        $tokens = [];
        foreach ($this->tokenKeys as $key) {
            // allow tokenKeys items to be array with prefix as the second
            // array element, ex: ['user', '-u '], so that it will parse
            // %%USER%% into '-u <$this->data['user']>', or
            // ex: ['pass', '-p'] will replace %%PASS%% into -p<$this->data['pass']>
            $prefix = "";
            if (is_array($key)) {
                $prefix = $key[1];
                $key = $key[0];
            }

            // skip if don't have data for the token available
            if (!isset($this->data[$key]) || $this->data[$key] === null) {
                continue;
            }

            $tokens[strtoupper($key)] = $prefix . escapeshellarg($this->data[$key]);
        }

        // return a combined command for all paths if in batch mode
        if ($this->data['batch']) {
            $tokens['PATH'] = implode(" ", $this->data['path']);
            return $this->getCommand($this->data['cmd'], $tokens);
        }

        // get an array of standalone commands in non-batch mode
        $cmds = [];
        foreach ($this->data['path'] as $path) {
            $tokens['PATH'] = $path;
            $cmds []= $this->getCommand($this->data['cmd'], $tokens);
        }

        return $cmds;
    }

    /**
     * Construct a single command from $cmd template and given $tokens
     *
     * @param string $cmd Command template
     * @param array $tokens List of tokens to subsctitude in command template
     *
     * @return string final command
     */
    protected function getCommand($cmd, $tokens)
    {
        return Template::parse($cmd, $tokens, '%%', '%%', Template::FLAG_RECURSIVE | Template::FLAG_EMPTY_MISSING);
    }
}
