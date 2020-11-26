<?php

namespace app\repository;

use yii\db\ActiveRecordInterface;

/**
 * Class Repository
 *
 * @package app\repository
 */
abstract class Repository
{
    /**
     * @var ActiveRecordInterface
     */
    protected $model;

    /**
     * @param ActiveRecordInterface $model
     */
    public function __construct(ActiveRecordInterface $model)
    {
        $this->model = $model;
    }

    /**
     * @return ActiveRecordInterface
     */
    public function getModel(): ActiveRecordInterface
    {
        return $this->model;
    }
}
