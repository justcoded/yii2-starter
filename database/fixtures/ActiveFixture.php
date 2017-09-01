<?php

namespace app\fixtures;

abstract class ActiveFixture extends \yii\test\ActiveFixture
{
	/**
	 * Loads the fixture.
	 *
	 * It populate the table with the data returned by [[getData()]].
	 *
	 * If you override this method, you should consider calling the parent implementation
	 * so that the data returned by [[getData()]] can be populated into the table.
	 */
	public function load()
	{
		$this->data = [];
		$table = $this->getTableSchema();
		// we will check that our fixture data has real db column keys and ignore missing in DB
		$columns = array_keys($table->columns);
		$columns = array_flip($columns);
		foreach ($this->getData() as $alias => $row) {
			$safeRow = array_intersect_key($row, $columns);
			$primaryKeys = $this->db->schema->insert($table->fullName, $safeRow);
			$this->data[$alias] = array_merge($row, $primaryKeys);
		}
	}
}