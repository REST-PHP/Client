<?php

declare(strict_types=1);

namespace Rest\Tests\Http\Components\Segments;

use PHPUnit\Framework\TestCase;
use Rest\Http\Components\Segments\MutableSegmentCollection;

/**
 * @internal
 */
class MutableSegmentTest extends TestCase
{
    public function test_setting_segment()
    {
        $collection = new MutableSegmentCollection();
        $collection->set('testing', 'value');

        $this->assertSame('value', $collection->get('testing'));
    }

    public function test_getting_all_segments()
    {
        $collection = new MutableSegmentCollection();
        $collection
            ->set('test1', 'value1')
            ->set('test2', 'value2');

        $this->assertEqualsCanonicalizing([
            'test1' => 'value1',
            'test2' => 'value2',
        ], $collection->all());
    }

    public function test_applying_segments()
    {
        $url = '/companies/{companyId}/users/{userId}';
        $collection = new MutableSegmentCollection();

        $collection
            ->set('companyId', '15')
            ->set('userId', '1');

        $this->assertSame('/companies/15/users/1', $collection->apply($url));
    }
}
