<?php

namespace tests\unit;

use app\models\Article;
use app\models\ModelFactory;
use app\repository\ArticleRepository;
use app\service\ArticleService;
use app\service\CacheService;
use app\service\CategoryService;
use Codeception\Test\Unit;
use yii\redis\Cache;

class ArticlesTest extends Unit
{
    public function testShow()
    {
        $model = new Article();
        $modelFactory = $this->getMockBuilder(ModelFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $cacheService = $this->getMockBuilder(CacheService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $articleRepository = $this->getMockBuilder(ArticleRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findById'])
            ->getMock();
        $articleRepository
            ->method('findById')
            ->willReturn($model);

        $service = new ArticleService(
            $modelFactory,
            $articleRepository,
            $cacheService
        );

        $article = $service->show(1);
        $this->assertInstanceOf(Article::class, $article);
    }

    public function testListExistAndArticleInCacheAndArticlesNotChanced()
    {
        $cache = $this->getMockBuilder(Cache::class)
            ->setMethods(['get','delete', 'add', 'set'])
            ->disableOriginalConstructor()
            ->getMock();
        $cache->expects($this->at(0))
            ->method('get')
            ->with(1)
            ->will($this->returnValue([
                'text' => 'test',
                'title' => 'test title',
            ]));

        $cache->expects($this->at(1))
            ->method('get')
            ->with('changed:'. 1)
            ->will($this->returnValue(false));
        $cacheService = $this->getMockBuilder(CacheService::class)
            ->setMethods(['getCache'])
            ->disableOriginalConstructor()
            ->getMock();
        $cacheService
            ->method('getCache')
            ->willReturn($cache);

        $modelFactory = $this->getMockBuilder(ModelFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $articleRepository = $this->getMockBuilder(ArticleRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findByCategoryId'])
            ->getMock();

        $articleRepository
            ->expects($this->never())
            ->method('findByCategoryId')
            ->willReturn([]);

        $service = new ArticleService(
            $modelFactory,
            $articleRepository,
            $cacheService
        );

        $article = $service->list(1);

        $this->assertEquals([
            'text' => 'test',
            'title' => 'test title',
        ], $article);
    }

    public function testListExistAndArticleInCacheAndArticlesChanced()
    {
        $cache = $this->getMockBuilder(Cache::class)
            ->setMethods(['get','delete', 'add', 'set'])
            ->disableOriginalConstructor()
            ->getMock();
        $cache->expects($this->at(0))
            ->method('get')
            ->with(1)
            ->will($this->returnValue([
                'text' => 'test',
                'title' => 'test title',
            ]));

        $cache->expects($this->at(1))
            ->method('get')
            ->with('changed:'. 1)
            ->will($this->returnValue(true));
        $cacheService = $this->getMockBuilder(CacheService::class)
            ->setMethods(['getCache'])
            ->disableOriginalConstructor()
            ->getMock();
        $cacheService
            ->method('getCache')
            ->willReturn($cache);

        $modelFactory = $this->getMockBuilder(ModelFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $articleRepository = $this->getMockBuilder(ArticleRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findByCategoryId'])
            ->getMock();

        $articleRepository
            ->expects($this->once())
            ->method('findByCategoryId')
            ->willReturn([
                'text' => 'new text',
                'title' => 'test title',
            ]);

        $service = new ArticleService(
            $modelFactory,
            $articleRepository,
            $cacheService
        );

        $article = $service->list(1);

        $this->assertEquals([
            'text' => 'new text',
            'title' => 'test title',
        ], $article);
    }

    public function testEdit()
    {
        $cache = $this->getMockBuilder(Cache::class)
            ->setMethods(['set'])
            ->disableOriginalConstructor()
            ->getMock();

        $cacheService = $this->getMockBuilder(CacheService::class)
            ->setMethods(['getCache'])
            ->disableOriginalConstructor()
            ->getMock();
        $cacheService
            ->method('getCache')
            ->willReturn($cache);

        $modelFactory = $this->getMockBuilder(ModelFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $model = $this->getMockBuilder(Article::class)
            ->setMethods(['save', 'attributes'])
            ->disableOriginalConstructor()
            ->getMock();
        $model
            ->expects($this->once())
            ->method('save')
            ->willReturn(true);
        $model
            ->method('attributes')
            ->willReturn([
                'id',
                'title',
                'text',
                'category_id',
            ]);
        $model->category_id = 1;

        $articleRepository = $this->getMockBuilder(ArticleRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findById'])
            ->getMock();

        $articleRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($model);

        $service = new ArticleService(
            $modelFactory,
            $articleRepository,
            $cacheService
        );
        $text = 'test text';
        $article = $service->edit(1, 'test title', $text);

        $this->assertEquals($text, $article->text);
    }

    public function testListArticlesNotExistInCache()
    {
        $cache = $this->getMockBuilder(Cache::class)
            ->setMethods(['get','delete', 'add', 'set'])
            ->disableOriginalConstructor()
            ->getMock();
        $cache->expects($this->exactly(1))
            ->method('get')
            ->with(1)
            ->willReturn(false);

        $cacheService = $this->getMockBuilder(CacheService::class)
            ->setMethods(['getCache'])
            ->disableOriginalConstructor()
            ->getMock();
        $cacheService
            ->method('getCache')
            ->willReturn($cache);
        $modelFactory = $this->getMockBuilder(ModelFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $articleRepository = $this->getMockBuilder(ArticleRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findByCategoryId'])
            ->getMock();
        $articleRepository->expects($this->exactly(1))->method('findByCategoryId');
        $articleRepository
            ->method('findByCategoryId')
            ->willReturn([]);

        $service = new ArticleService(
            $modelFactory,
            $articleRepository,
            $cacheService
        );

        $article = $service->list(1);
        $this->assertEquals([], $article);
    }
}
