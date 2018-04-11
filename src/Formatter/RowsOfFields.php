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
namespace Qobo\Robo\Formatter;

use Qobo\Robo\Utility\Hash;
use Consolidation\OutputFormatters\Options\FormatterOptions;
use Consolidation\OutputFormatters\StructuredData\RowsOfFields;

class RowsOfFields extends RowsOfFields
{
    public function renderCell($key, $cellData, FormatterOptions $options, $rowData)
    {
        return $this->flatten($cellData);
    }

    protected function flatten($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        if (!$this->isAssoc($data)) {
            return implode(",\n", array_map([$this, "flatten" ], $data));
        }

        return implode("\n", array_map(
            function ($k, $v) {
                return "$k: $v";
            },
            array_keys($data),
            array_values($data)
        ));
    }

    protected function isAssoc($arr)
    {
        if (array() === $arr) {
            return false;
        }
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
