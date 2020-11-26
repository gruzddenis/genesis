<?php

namespace app\controllers;

use app\models\Category;
use app\service\CategoryService;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * Class CategoryController
 *
 * @package app\controllers
 */
class CategoryController extends Controller
{
    /**
     * @var CategoryService
     */
    protected $service;

    /**
     * @param CategoryService $service
     * @param $id
     * @param $module
     * @param array $config
     */
    public function __construct($id, $module, CategoryService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * @param $action
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        $this->response->format = Response::FORMAT_JSON;

        return parent::beforeAction($action);
    }

    /**
     * @param int $id
     *
     * @return Category
     */
    public function actionShow(int $id): Category
    {
        return $this->service->show($id);
    }

    /**
     * @return array
     */
    public function actionList(): array
    {
        return $this->service->list();
    }

    /**
     * @param int $id
     *
     * @return Category
     */
    public function actionEdit(int $id): Category
    {
        return $this->service->edit($id, $this->request->post('name'));
    }

    /**
     * @return Category
     * @throws \Throwable
     */
    public function actionAdd(): Category
    {
        return $this->service->add($this->request->post('name'));
    }

    /**
     * @param int $id
     * @return bool
     *
     * @throws \Throwable
     */
    public function actionDelete(int $id): bool
    {
        return $this->service->delete($id);
    }
}