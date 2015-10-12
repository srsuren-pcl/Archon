<?php

/**
 * Contains the DataFrame class.
 * @package   DataFrame
 * @author    Howard Gehring <hwgehring@gmail.com>
 * @copyright 2015 Howard Gehring <hwgehring@gmail.com>
 * @license   https://github.com/HWGehring/Archon/blob/master/LICENSE BSD-3-Clause
 * @link      https://github.com/HWGehring/Archon
 * @since     0.1.0
 */

namespace Archon;

use Archon\IO\CSV;
use Archon\IO\FWF;
use Archon\IO\HTML;
use Archon\IO\SQL;
use PDO;

/**
 * The DataFrame class acts as an interface to various underlying data structure, file format, and database
 * implementations.
 * @package   Archon
 * @author    Howard Gehring <hwgehring@gmail.com>
 * @copyright 2015 Howard Gehring <hwgehring@gmail.com>
 * @license   https://github.com/HWGehring/Archon/blob/master/LICENSE BSD-3-Clause
 * @link      https://github.com/HWGehring/Archon
 * @since     0.1.0
 */
final class DataFrame extends DataFrameCore
{

    protected function __construct(array $data)
    {
        parent::__construct($data);
    }

    /**
     * Factory method for creating a DataFrame from a CSV file.
     * @param  $fileName
     * @param  array $options
     * @return DataFrame
     * @since  0.1.0
     */
    public static function fromCSV($fileName, $options = [])
    {
        $csv = new CSV($fileName);
        $data = $csv->loadFile($options);
        return new DataFrame($data);
    }

    /**
     * Outputs a DataFrame to a CSV file.
     * @param  $fileName
     * @param  array $options
     * @return $this
     * @throws \Archon\Exceptions\FileExistsException
     * @since  0.1.0
     */
    public function toCSV($fileName, $options = [])
    {
        $csv = new CSV($fileName);
        $csv->saveFile($this->data, $options);
        return $this;
    }

    /**
     * Factory method for creating a DataFrame from a fixed-width file.
     * @param  $fileName
     * @param  array $colSpecs
     * @param  array $options
     * @return DataFrame
     * @since  0.1.0
     */
    public static function fromFWF($fileName, array $colSpecs, array $options = [])
    {
        $fwf = new FWF($fileName);
        $data = $fwf->loadFile($colSpecs, $options);
        return new DataFrame($data);
    }

    public function toSQL(PDO $pdo, $tableName, array $options = [])
    {
        $sql = new SQL($pdo);
        $sql->insertInto($tableName, $this->columns, $this->data, $options);
    }
    /**
     * Outputs a DataFrame to an HTML string.
     * @param  array $options
     * @return array
     * @throws \Archon\Exceptions\NotYetImplementedException
     * @since  0.1.0
     */
    public function toHTML($options = [])
    {
        $html = new HTML($this->data);
        $output = $html->assembleTable($options);
        return $output;
    }

    /**
     * Factory method for creating a DataFrame from a two-dimensional associative array.
     * @param  array $data
     * @param  array $options
     * @return DataFrame
     * @since  0.1.0
     */
    public static function fromArray(array $data, array $options = [])
    {
        $firstRow = current($data);
        $columns = isset($options['columns']) ? $options['columns'] : array_keys($firstRow);

        foreach ($data as &$row) {
            $row = array_combine($columns, $row);
        }

        return new DataFrame($data);
    }

    /**
     * Outputs a DataFrame as a two-dimensional associative array.
     * @return array
     * @since 0.1.0
     */
    public function toArray()
    {
        return $this->data;
    }
}
