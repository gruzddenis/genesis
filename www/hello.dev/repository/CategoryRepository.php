<?php

namespace app\repository;

use app\models\Category;

/**
 * Class CategoryRepository
 *
 * @package app\repository
 */
class CategoryRepository extends Repository
{
    /**
     * @param int $id
     *
     * @return null|Category
     */
    public function findById(int $id): ?Category
    {
        return $this->getModel()::find()->where('id=' . $id)->limit(1)->one();
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return $this->getModel()::find()->all();
    }
}
