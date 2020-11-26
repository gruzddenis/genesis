<?php

namespace app\repository;

use app\models\Article;
use yii\db\ActiveRecordInterface;

/**
 * Class ArticleRepository
 *
 * @package app\repository
 */
class ArticleRepository extends Repository
{
    /**
     * @param int $id
     *
     * @return Article
     */
    public function findById(int $id): Article
    {
        return $this->getModel()::find()->where('id=' . $id)->limit(1)->one();
    }

    public function findByCategoryId(int $categoryId): array
    {
        return $this->getModel()::find()->where('category_id=' . $categoryId)->asArray()->all();
    }
}
