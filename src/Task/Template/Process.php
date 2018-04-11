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
use Qobo\Robo\AbstractTask;
use Qobo\Robo\Utility\File;
use Qobo\Robo\Utility\Template;
use Robo\Result;

/**
 * Parse template file
 *
 * ```php
 * <?php
 * $this->taskTemplateProcess()
 * ->src('template.ctp')
 * ->dst('parsed_file.txt')
 * ->wrap('%%')
 * ->tokens(['key1' => 'value1', 'key2' => 'value2'])
 * ->run();
 *
 *<?php
 * $this->taskTemplateProcess()
 * ->src('template.ctp')
 * ->dst('parsed_file.txt')
 * ->pre('{{')
 * ->post('}}')
 * ->tokens(['key1' => 'value1', 'key2' => 'value2'])
 * ->run();
 *
 * ?>
 * ```
 */
class Process extends AbstractTask
{
    /**
     * {@inheritdoc}
     */
    protected $data = [
        'pre'           => '%%',
        'post'          => '%%',
        'src'           => null,
        'dst'           => null,
        'tokens'        => []
    ];

    /**
     * {@inheritdoc}
     */
    protected $requiredData = [
        'pre',
        'post',
        'src',
        'dst'
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

        $this->printInfo("Processing {src} template to {dst} file", $this->data);

        try {
            $template = File::read($this->data['src']);
            $this->data['data'] = Template::parse(
                $template,
                $this->data['tokens'],
                $this->data['pre'],
                $this->data['post']
            );
            File::write($this->data['dst'], $this->data['data']);
        } catch (Exception $e) {
            return Result::fromException($this, $e);
        }

        return Result::success($this, "Successfully parsed template", $this->data);
    }
}
