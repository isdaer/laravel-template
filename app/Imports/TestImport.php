<?php

namespace App\Imports;

use PhpOffice\PhpSpreadsheet\IOFactory;

class TestImport
{
    protected $highest_column;

    public function setHighestColumn($column = null)
    {
        $this->highest_column = $column;

        return $this;
    }

    public function importExcel($temp_file)
    {
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($temp_file); //载入excel表格
        $worksheet = $spreadsheet->getSheet(0);

        $highestRow = $worksheet->getHighestDataRow(); // 有数据的总行数
        $highestColumn = $worksheet->getHighestDataColumn(); // 有数据的总列数

        $data = [];

        for ($row = 1; $row <= $highestRow; $row++) //行号从1开始
        {
            $values = [];
            for ($column = 'A'; $column <= $highestColumn; $column++) //列数是以A列开始
            {
                $value = $worksheet->getCell($column . $row)->getValue();
                $values[] = $value;
            }
            $data[] = $values;
        }
        return $data;
    }
}
