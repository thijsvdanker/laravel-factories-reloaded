<?php

namespace Christophrumpel\LaravelFactoriesReloaded\Tests\Factories;

use Christophrumpel\LaravelFactoriesReloaded\BaseFactory;
use Christophrumpel\LaravelFactoriesReloaded\Tests\Models\Recipe;
use Faker\Generator;

class RecipeFactory extends BaseFactory
{

    /**
     * @var string
     */
    protected $modelClass = Recipe::class;

    public function create(array $extra = []): Recipe
    {
        return parent::build($extra);
    }

    public function make(array $extra = []): Recipe
    {
        return parent::build($extra, 'make');
    }

    public function getData(Generator $faker): array
    {
        return [
            'name' => $faker->name,
            'description' => 'Our family lasagne recipe.'
        ];
    }

}
