<?php

namespace Christophrumpel\LaravelFactoriesReloaded;

use Faker\Factory as FakerFactory;
use Illuminate\Support\Collection;

abstract class BaseFactory implements FactoryInterface
{

    /**
     * @var string
     */
    protected $modelClass;

    private $relatedModel;

    /**
     * @var string
     */
    private $relatedModelRelationshipName;

    private $relatedModelTimes;

    public static function new(): self
    {
        return new static;
    }

    public function create(array $extra = [])
    {
        $model = $this->modelClass::create(array_merge($this->getData(FakerFactory::create()), $extra));

        if ($this->relatedModel) {
            collect()
            ->times($this->relatedModelTimes)
            ->each(function($time) use ($model) {
                $model->{$this->relatedModelRelationshipName}()
                    ->save($this->relatedModel->make());
            });
        }

        return $model;

    }

    public function times(int $times, array $extra = []): Collection
    {
        return collect()
            ->times($times)
            ->transform(function() use ($extra) {
                return $this->create($extra);
           });
    }

    public function with(string $relatedModelClass, string $relationshipName, int $times = 1)
    {
        $this->relatedModel = $this->getFactoryFromClassName($relatedModelClass);
        $this->relatedModelRelationshipName = $relationshipName;
        $this->relatedModelTimes = $times;
        return $this;
    }

    private function getFactoryFromClassName(string $className): FactoryInterface
    {
        $baseClassName = (new \ReflectionClass($className))->getShortName();
        $factoryClass = config('factories-reloaded.factories_namespace').'\\'.$baseClassName.'Factory';

        return new $factoryClass;
    }
}
