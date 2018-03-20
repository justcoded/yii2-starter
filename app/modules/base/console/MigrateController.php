<?php

namespace app\modules\base\console;


use yii\console\Exception;

class MigrateController extends \yii\console\controllers\MigrateController
{
	/**
	 * @inheritdoc
	 */
	public $templateFile = '@app/modules/base/views/migrate/migration.php';

	/**
	 * @inheritdoc
	 */
	public $generatorTemplateFiles = [
		'create_table' => '@app/modules/base/views/migrate/createTableMigration.php',
		'drop_table' => '@app/modules/base/views/migrate/dropTableMigration.php',
		'add_column' => '@app/modules/base/views/migrate/addColumnMigration.php',
		'drop_column' => '@app/modules/base/views/migrate/dropColumnMigration.php',
		'create_junction' => '@app/modules/base/views/migrate/createTableMigration.php',
	];

	/**
	 * Creates a new migration for create table.
	 *
	 * ```
	 * yii migrate/create-table table
	 * ```
	 *
	 * @param string $name the name of the table
	 *
	 * @throws Exception if the name argument is invalid.
	 */
	public function actionCreateTable($name)
	{
		return $this->actionCreate("create_{$name}_table");
	}

	/**
	 * Creates a new migration for drop table.
	 *
	 * ```
	 * yii migrate/create-droptable table
	 * ```
	 *
	 * @param string $name the name of the table.
	 *
	 * @throws Exception if the name argument is invalid.
	 */
	public function actionCreateDroptable($name)
	{
		return $this->actionCreate("drop_{$name}_table");
	}

	/**
	 * Creates a new migration for create column.
	 *
	 * ```
	 * yii migrate/create-column column table
	 * ```
	 *
	 * @param string $column the name of the column
	 * @param string $table the name of the table
	 *
	 * @throws Exception if the name argument is invalid.
	 */
	public function actionCreateColumns($column, $table)
	{
		return $this->actionCreate("add_{$column}_columns_to_{$table}_table");
	}

	/**
	 * Creates a new migration for drop column
	 *
	 * ```
	 * yii migrate/create-dropcolumn column table
	 * ```
	 *
	 * @param string $column the name of the column
	 * @param string $table the name of the table
	 *
	 * @throws Exception if the name argument is invalid.
	 */
	public function actionCreateDropcolumns($column, $table)
	{
		return $this->actionCreate("drop_{$column}_columns_from_{$table}_table");
	}

	/**
	 * Creates a new migration for junction table.
	 *
	 * ```
	 * yii migrate/create-junction primary_table secondary_table
	 * ```
	 *
	 * @param string $primary the name of the primary table
	 * @param string $secondary the name of the secondary table
	 *
	 * @throws Exception if the name argument is invalid.
	 */
	public function actionCreateJunction($primary, $secondary)
	{
		return $this->actionCreate("create_junction_for_{$primary}_and_{$secondary}_tables");
	}
}
