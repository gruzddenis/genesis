<?php

namespace app\service;

use app\models\Article;
use app\models\ModelFactory;
use app\repository\ArticleRepository;
use yii\caching\CacheInterface;
use yii\db\ActiveRecord;

/**
 * Class ArticleService
 *
 * @package app\service
 */
class ArticleService
{
    /**
     * @var ModelFactory
     */
    private $modelFactory;

    /**
     * @var ArticleRepository
     */
    private $repository;

    /**
     * @var CacheService
     */
    private $cacheService;

    /**
     * @param ModelFactory $modelFactory
     * @param ArticleRepository $repository
     * @param CacheService $cacheService
     */
    public function __construct
    (
        ModelFactory $modelFactory,
        ArticleRepository $repository,
        CacheService $cacheService
    ) {
        $this->modelFactory = $modelFactory;
        $this->repository = $repository;
        $this->cacheService = $cacheService;
    }

    /**
     * @param $id
     *
     * @return Article
     */
    public function show($id): Article
    {
        return $this->repository->findById($id);
    }

    /**
     * @param int $categoryId
     *
     * @return array
     */
    public function list(int $categoryId): array
    {
        $cache = $this->cacheService->getCache();
        $articles = $cache->get($categoryId);

        if (!$articles) {
            $articles = $this->putArticlesInCache($cache, $categoryId);
        } else {
            $changed = $cache->get($this->getCacheFlagName($categoryId));

            if ($changed) {
                $cache->delete($categoryId);
                $articles = $this->putArticlesInCache($cache, $categoryId);
            }
        }

       return $articles;
    }

    /**
     * @param int $id
     * @param string $title
     * @param string $text
     *
     * @return Article
     */
    public function edit(int $id, string $title, string $text): Article
    {
        $article = $this->repository->findById($id);
        $article->text = $text;
        $article->title = $title;
        $updated = $article->save();

        if ($updated) {
            $cache = $this->cacheService->getCache();
            $cache->set($this->getCacheFlagName($article->category_id), 'true');
        }

        return $article;
    }

    /**
     * @param int $categoryId
     * @param string $title
     * @param string $text
     *
     * @return Article
     * @throws \Throwable
     */
    public function add(int $categoryId, string $title, string $text): Article
    {
        return Article::getDb()->transaction(function() use ($categoryId, $title, $text) {
            $article = $this->getModel();
            $article->text = $text;
            $article->title = $title;
            $article->category_id = $categoryId;
            $created = $article->save();

            if ($created) {
                $article->category->updateCounters(['count_articles' => 1]);
            }

            return $article;
        });
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        return Article::getDb()->transaction(function() use ($id) {
            $article = $this->repository->findById($id);
            $deleted = $article->delete();

            if ($deleted) {
                $category = $article->getCategory()->limit(1)->one();
                $category->count_articles -= 1;
                $category->save();
            }

            return $deleted;
        });
    }

    /**
     * @param CacheInterface $cache
     * @param int $categoryId
     *
     * @return array
     */
    private function putArticlesInCache(CacheInterface $cache,int $categoryId): array
    {
        $articles = $this->repository->findByCategoryId($categoryId);

        if (count($articles)) {
            $cache->add($categoryId, $articles);
            $cache->set('changed:'. $categoryId, false);
        }

        return $articles;
    }

    /**
     * @return ActiveRecord
     */
    private function getModel(): ActiveRecord
    {
        return $this->modelFactory->get(Article::class);
    }

    /**
     * @param int $categoryId
     *
     * @return string
     */
    private function getCacheFlagName(int $categoryId): string
    {
        return 'changed:'. $categoryId;
    }
}
