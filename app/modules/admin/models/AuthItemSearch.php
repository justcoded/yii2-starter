<?php

namespace app\modules\admin\models;

use app\models\AuthItems;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;
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
		// bypass scenarios() implementation in the parent class
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

		// add conditions that should always apply here

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		if ( ! $this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		$query->andFilterWhere(['like', 'name', $this->name]);


		return $dataProvider;
	}

	public function searchPermissions($params)
	{
		$query = AuthItems::find()->where(['type' => Role::TYPE_PERMISSION]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		if ( ! $this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		$query->andFilterWhere(['like', 'name', $this->permission]);


		return $dataProvider;
	}

}
