<?php

namespace app\modules\admin\models;

use app\models\AuthItems;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\rbac\Role;


class AuthItemSearch extends AuthItems
{

	public $roles;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'permission'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios()
	{
		return Model::scenarios();
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function searchRoles($params)
	{
		$query = AuthItems::find()->where(['type' => Role::TYPE_ROLE]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		if ( ! $this->validate()) {

			return $dataProvider;
		}

		$query->andFilterWhere(['like', 'name', $this->name]);


		return $dataProvider;
	}

	/**
	 * @param $params
	 * @return ActiveDataProvider
	 */
	public function searchPermissions($params)
	{
		$query = AuthItems::find()->where(['type' => Role::TYPE_PERMISSION]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		if ( ! $this->validate()) {

			return $dataProvider;
		}

		$query->andFilterWhere(['like', 'name', $this->permission]);

		return $dataProvider;
	}

}
