<?php

declare(strict_types=1);

namespace Rest\Tests\Http\Parameters;

use PHPUnit\Framework\TestCase;
use Rest\Http\Parameters\MutableParameterCollection;

/**
 * @internal
 */
class MutableParameterCollectionTest extends TestCase
{
    public function test_adding_a_parameter()
    {
        $collection = new MutableParameterCollection();
        $collection
            ->add('test-key', 'test-value1')
            ->add('test-key', 'test-value2');

        $this->assertSame(['test-value1', 'test-value2'], $collection->get('test-key'));
    }

    public function test_setting_a_parameter()
    {
        $collection = new MutableParameterCollection();
        $collection
            ->add('test-key', 'test-value')
            ->set('test-key', 'test-value1');

        $this->assertSame(['test-value1'], $collection->get('test-key'));
    }

    public function test_unsetting_a_parameter()
    {
        $collection = new MutableParameterCollection();
        $collection
            ->add('test-key', 'test-value')
            ->unset('test-key');

        $this->assertNull($collection->get('test-key'));
    }

    public function test_merging_parameters()
    {
        $collection = new MutableParameterCollection();
        $collection
            ->add('test-key-2', 'test-key-2-value-1')
            ->merge([
                'test-key-1' => ['test-key-1-value-1', 'test-key-1-value-2'],
                'test-key-2' => 'test-key-2-value-2',
                'test-key-3' => 'test-key-3-value-3',
            ]);

        $this->assertSame(['test-key-1-value-1', 'test-key-1-value-2'], $collection->get('test-key-1'));
        $this->assertSame(['test-key-2-value-1', 'test-key-2-value-2'], $collection->get('test-key-2'));
        $this->assertSame(['test-key-3-value-3'], $collection->get('test-key-3'));
    }

    public function test_replacing_parameters()
    {
        $collection = new MutableParameterCollection();
        $collection
            ->add('test-key', 'test-value')
            ->replace([
                'test-key-1' => 'test-value-1',
            ]);

        $this->assertNull($collection->get('test-key'));
        $this->assertSame(['test-value-1'], $collection->get('test-key-1'));
    }

    public function test_getting_first_value_of_parameter()
    {
        $collection = new MutableParameterCollection();
        $collection->merge(['test-key' => ['test-value-1', 'test-value-2']]);

        $this->assertSame('test-value-1', $collection->first('test-key'));
    }

    public function test_getting_all_parameters()
    {
        $collection = new MutableParameterCollection();
        $collection->merge([
            'test-key-1' => ['test-value-1', 'test-value-2'],
            'test-key-2' => 'test-value-1',
        ]);

        $this->assertEqualsCanonicalizing(
            [
                'test-key-1' => ['test-value-1', 'test-value-2'],
                'test-key-2' => ['test-value-1'],
            ],
            $collection->all()
        );
    }
}
