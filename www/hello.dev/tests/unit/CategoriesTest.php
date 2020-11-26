<?php

namespace tests\unit;

use app\models\Category;
use app\models\ModelFactory;
use app\repository\CategoryRepository;
use app\service\CategoryService;
use Codeception\Test\Unit;

class CategoriesTest extends Unit
{
    public function testShow()
    {
        $model = new Category();
        $modelFactory = $this->getMockBuilder(ModelFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $articleRepository = $this->getMockBuilder(CategoryRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findById'])
            ->getMock();
            $articleRepository->expects(self::once())
            ->method('findById')
            ->willReturn($model);

        $service = new CategoryService($modelFactory, $articleRepository);

        $category = $service->show(1);
        $this->assertInstanceOf(Category::class, $category);
    }

    public function testList()
    {
        $array = [
            'text' => 'test',
            'title' => 'test title',
        ];
        $modelFactory = $this->getMockBuilder(ModelFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $articleRepository = $this->getMockBuilder(CategoryRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findAll'])
            ->getMock();
        $articleRepository->expects(self::once())
            ->method('findAll')
            ->willReturn($array);

        $service = new CategoryService($modelFactory, $articleRepository);

        $categories = $service->list();

        $this->assertEquals([
            'text' => 'test',
            'title' => 'test title',
        ], $categories);
    }

    public function testEdit()
    {
        $model = $this->getMockBuilder(Category::class)
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
                'name',
            ]);

        $modelFactory = $this->getMockBuilder(ModelFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $articleRepository = $this->getMockBuilder(CategoryRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findById'])
            ->getMock();
        $articleRepository->expects(self::once())
            ->method('findById')
            ->willReturn($model);

        $service = new CategoryService($modelFactory, $articleRepository);
        $name = 'test category';

        $category = $service->edit(1, $name);

        $this->assertEquals($name, $category->name);
    }

    public function testAdd()
    {
        $model = $this->getMockBuilder(Category::class)
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
                'name',
            ]);

        $modelFactory = $this->getMockBuilder(ModelFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $modelFactory->expects(self::once())
            ->method('get')
            ->willReturn($model);

        $articleRepository = $this->getMockBuilder(CategoryRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service = new CategoryService($modelFactory, $articleRepository);

        $name = 'test category';

        $category = $service->add($name);

        $this->assertEquals($name, $category->name);
    }

    public function testDelete()
    {
        $model = $this->getMockBuilder(Category::class)
            ->setMethods(['delete'])
            ->disableOriginalConstructor()
            ->getMock();
        $model
            ->expects($this->once())
            ->method('delete')
            ->willReturn(true);

        $modelFactory = $this->getMockBuilder(ModelFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $articleRepository = $this->getMockBuilder(CategoryRepository::class)
            ->disableOriginalConstructor()
            ->setMethods(['findById'])
            ->getMock();
        $articleRepository->expects(self::once())
            ->method('findById')
            ->willReturn($model);

        $service = new CategoryService($modelFactory, $articleRepository);

        $category = $service->delete(1);

        $this->assertEquals(true, $category);
    }
}
