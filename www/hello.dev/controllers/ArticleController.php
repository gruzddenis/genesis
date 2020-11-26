<?php

namespace app\controllers;

use app\models\Article;
use app\service\ArticleService;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class ArticleController
 *
 * @package app\controllers
 */
class ArticleController extends Controller
{
    /**
     * @var ArticleService
     */
    protected $service;

    /**
     * @param ArticleService $service
     * @param $id
     * @param $module
     * @param array $config
     */
    public function __construct($id, $module, ArticleService $service, $config = [])
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
     * @return Article
     */
    public function actionShow(int $id): Article
    {
        return $this->service->show($id);
    }

    /**
     * @param int $categoryId
     *
     * @return array
     */
    public function actionList(int $categoryId): array
    {
        return $this->service->list($categoryId);
    }

    /**
     * @param int $id
     *
     * @return Article
     */
    public function actionEdit(int $id): Article
    {
        $title = $this->request->post('title');
        $text = $this->request->post('text');

        return $this->service->edit($id, $title, $text);
    }

    /**
     * @return Article
     * @throws \Throwable
     */
    public function actionAdd(): Article
    {
        $title = $this->request->post('title');
        $text = $this->request->post('text');
        $categoryId = $this->request->post('category_id');

        return $this->service->add($categoryId, $title, $text);
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
