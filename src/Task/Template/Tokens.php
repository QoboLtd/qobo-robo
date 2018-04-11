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
namespace Qobo\Robo\Task\Template;

use Exception;
use Robo\Result;
use Qobo\Robo\Utility\File;
use Qobo\Robo\Utility\Template;

/**
 * List all tokens from template file
 *
 * ```php
 * <?php
 * $this->taskTemplateTokens()
 * ->path('template.ctp')
 * ->wrap('###')
 * ->run();
 *
 *<?php
 * $this->taskTemplateTokens()
 * ->path('template.ctp')
 * ->pre('{{')
 * ->post('}}')
 * ->run();
 *
 * ?>
 * ```
 */
class Tokens extends \Qobo\Robo\AbstractTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'path'          => null,
        'pre'           => '%%',
        'post'          => '%%'
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredData = [
        'path',
        'pre',
        'post'
    ];

    /**
     * Shortcut for setting same pre and post
     *
     * @param string $str Prefix and postfix for template tocken
     *
     * @return $this
     */
    public function wrap($str)
    {
        $this->data['pre'] = $str;
        $this->data['post'] = $str;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $result = parent::run();
        if (!$result->wasSuccessful()) {
            return $result;
        }

        $this->printInfo("Retrieving list of tokens from {path} template", $this->data);

        try {
            $template = File::read($this->data['path']);
            $this->data['data'] = Template::getTokens($template, $this->data['pre'], $this->data['post']);
        } catch (Exception $e) {
            return Result::fromException($this, $e);
        }

        return Result::success($this, "Successfully retrieved list of tokens", $this->data);
    }
}
