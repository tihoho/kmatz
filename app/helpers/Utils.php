<?php

namespace app\helpers;

class Utils
{
	/**
	 * Convert assoc array to ASCII-table
	 *
	 * @param array $arr
	 * @return string
	 */
	public static function assocToTable($arr)
	{
		if (empty($arr) || !is_array($arr)) {
			return '';
		}

		$table = '';

		// Find max len in rows
		$columnLengths = array();
		foreach ($arr as $row) {
			foreach ($row as $key => $value) {
				$columnLengths[$key] = max(strlen($key), isset($columnLengths[$key]) ? $columnLengths[$key] : 0, strlen($value));
			}
		}

		// Headers
		$headerRow = '';
		foreach ($columnLengths as $column => $length) {
			$headerRow .= str_pad($column, $length) . ' | ';
		}
		$table .= rtrim($headerRow, ' | ') . "\n";

		// Rows
		foreach ($arr as $row) {
			$dataRow = '';
			foreach ($columnLengths as $column => $length) {
				$dataRow .= str_pad(isset($row[$column]) ? $row[$column] : '', $length) . ' | ';
			}
			$table .= rtrim($dataRow, ' | ') . "\n";
		}

		return $table;
	}

}
