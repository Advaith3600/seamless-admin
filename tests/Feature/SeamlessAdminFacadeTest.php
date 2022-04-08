<?php

namespace Advaith\SeamlessAdmin\Tests\Feature;

use Advaith\SeamlessAdmin\Tests\TestCase;
use Advaith\SeamlessAdmin\Facades\SeamlessAdmin;

/**
 * Check whether the SeamlessAdmin Facade can add custom routes properly
 */
class SeamlessAdminFacadeTest extends TestCase
{
    private int $count = 0;

    public function test_whether_seamlessAdmin_facade_is_empty_default()
    {
        $this->assertEmpty(SeamlessAdmin::getRoutes());
    }

    public function test_seamlessAdmin_facade_after_entry()
    {
        SeamlessAdmin::add('route-name', 'Alias');
        $this->assertCount(++$this->count, SeamlessAdmin::getRoutes());
    }

    public function test_seamlessAdmin_facade_after_optional_entry()
    {
        SeamlessAdmin::add('route-name-2', 'Alias', fn() => true);
        $this->assertCount(++$this->count, SeamlessAdmin::getRoutes());
    }

    public function test_seamlessAdmin_facade_after_optional_false_entry()
    {
        SeamlessAdmin::add('route-name-3', 'Alias', fn() => false);
        $this->assertCount($this->count, SeamlessAdmin::getRoutes());
    }
}
