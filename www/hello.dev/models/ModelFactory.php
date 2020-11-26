<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class ModelFactory
 *
 * @package app\Model
 */
class ModelFactory
{
    /**
     * @param string $model
     *
     * @return ActiveRecord
     */
    public function get(string $model): ActiveRecord
    {
        return new $model;
    }
}
