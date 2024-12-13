<?php

declare(strict_types=1);

namespace Rest\Tests\Http\Components\QueryParameters;

use PHPUnit\Framework\TestCase;
use Rest\Http\Components\QueryParameters\ImmutableQueryParameterCollection;
use Rest\Http\Components\QueryParameters\MutableQueryParameterCollection;

/**
 * @internal
 */
class MutableQueryParameterCollectionTest extends TestCase
{
    public function test_getting_query_parameters()
    {
        $parameters = [
            'search' => ['query'],
            'first_name' => ['Jim', 'Dwight'],
        ];

        $queryParameters = new MutableQueryParameterCollection($parameters);

        $this->assertSame('query', $queryParameters->get('search'));
        $this->assertEqualsCanonicalizing(['Jim', 'Dwight'], $queryParameters->get('first_name'));
        $this->assertNull($queryParameters->get('not_found'));
        $this->assertEqualsCanonicalizing($parameters, $queryParameters->all());
    }

    public function test_getting_first_query_parameter()
    {
        $queryParameters = new MutableQueryParameterCollection([
            'first_name' => ['Jim', 'Dwight'],
        ]);

        $this->assertSame('Jim', $queryParameters->first('first_name'));
    }

    public function test_adding_query_parameter()
    {
        $queryParameters = new MutableQueryParameterCollection();

        $queryParameters
            ->add('first_name', 'Jim')
            ->add('first_name', 'Dwight');

        $this->assertEqualsCanonicalizing(['Jim', 'Dwight'], $queryParameters->get('first_name'));
    }

    public function test_setting_query_parameter()
    {
        $queryParameters = new MutableQueryParameterCollection();

        $queryParameters
            ->add('first_name', 'Bob')
            ->set('first_name', 'Jim');

        $this->assertSame('Jim', $queryParameters->get('first_name'));
    }

    public function test_merging_collection_parameters()
    {
        $queryParameters = new MutableQueryParameterCollection([
            'first_name' => ['Jim', 'Dwight'],
        ]);

        $queryParameters->merge(['first_name' => 'Larry']);

        $this->assertEqualsCanonicalizing(
            ['Jim', 'Dwight', 'Larry'],
            $queryParameters->get('first_name')
        );
    }

    public function test_replacing_collection_parameters()
    {
        $queryParameters = new MutableQueryParameterCollection([
            'first_name' => ['Jim', 'Dwight'],
        ]);

        $queryParameters->replace(['first_name' => 'Larry']);

        $this->assertEqualsCanonicalizing(
            'Larry',
            $queryParameters->get('first_name')
        );
    }

    public function test_converting_to_immutable_collection()
    {
        $queryParameters = new MutableQueryParameterCollection([
            'first_name' => ['Jim'],
        ]);

        $queryParameters = $queryParameters->toImmutable();

        $this->assertInstanceOf(ImmutableQueryParameterCollection::class, $queryParameters);
    }
}
