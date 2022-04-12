<?php

namespace Advaith\SeamlessAdmin\Tests\Feature;

use Advaith\SeamlessAdmin\Tests\TestCase;
use Advaith\SeamlessAdmin\Facades\SeamlessAdmin;

/**
 * Testing different aspect of the ModelResolver
 */
class ModelResolverTest extends TestCase
{
    // test whether the model resolver parses the models correctly
    public function test_models_resolved()
    {
        $resolver = app('modelResolver');

        $this->assertCount(1, $resolver->getModels());
    }

    // test whether the model resolver parse the type string properly using cryptographic function
    // also check whether it can resolve the model from the table name
    public function test_model_resolver_parsing()
    {
        $resolver = app('modelResolver');
        $model = $resolver->getModels()[0];
        $type = $resolver->parseType($model);

        $this->assertEquals($model, $resolver->resolveType($type));
        $this->assertEquals($model, $resolver->resolveModel((new $model)->getTable()));
    }

    // how does the model resolver compares to while adding elements to the sidebar
    public function test_sidebar_elements_array()
    {
        SeamlessAdmin::add('test', 'Test');
        SeamlessAdmin::add('test-2', 'Test2', ['group' => 'custom']);
        $resolver = app('modelResolver');

        [$elements] = $resolver->getSidebarElements();
        $this->assertCount(2, $elements['_default']);
        $this->assertCount(1, $elements['custom']);
    }
}
