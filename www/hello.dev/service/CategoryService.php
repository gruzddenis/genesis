<?php

namespace app\service;

use app\models\Category;
use app\models\ModelFactory;
use app\repository\CategoryRepository;
use yii\db\ActiveRecord;

/**
 * Class CategoryService
 *
 * @package app\service
 */
class CategoryService
{
    /**
     * @var ModelFactory
     */
    private $modelFactory;

    /**
     * @var CategoryRepository
     */
    private $repository;

    /**
     * @param ModelFactory $modelFactory
     * @param CategoryRepository $repository
     */
    public function __construct(ModelFactory $modelFactory, CategoryRepository $repository)
    {
        $this->modelFactory = $modelFactory;
        $this->repository = $repository;
    }

    /**
     * @param $id
     *
     * @return Category
     */
    public function show($id): Category
    {
        return $this->repository->findById($id);
    }

    /**
     * @return array
     */
    public function list(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param int $id
     * @param string $name
     *
     * @return Category
     */
    public function edit(int $id, string $name): Category
    {
        $article = $this->repository->findById($id);
        $article->name = $name;
        $article->save();

        return $article;
    }

    /**
     * @param string $name
     *
     * @return Category
     * @throws \Throwable
     */
    public function add(string $name): Category
    {
        $article = $this->getModel();
        $article->name = $name;
        $article->save();

        return $article;

    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        return $this->repository->findById($id)->delete();
    }

    /**
     * @return ActiveRecord
     */
    private function getModel(): ActiveRecord
    {
        return $this->modelFactory->get(Category::class);
    }
}
