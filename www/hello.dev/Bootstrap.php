<?php

namespace app;

use app\models\Article;
use app\models\Category;
use app\repository\ArticleRepository;
use app\repository\CategoryRepository;
use yii\base\BootstrapInterface;
use yii\caching\CacheInterface;
use yii\redis\Cache;


class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton(ArticleRepository::class, function () {
            return new ArticleRepository(new Article());
        });
        $container->setSingleton(CategoryRepository::class, function () {
            return new CategoryRepository(new Category());
        });
        $container->set(CacheInterface::class, Cache::class);
    }
}