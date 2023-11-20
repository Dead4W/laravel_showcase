<?php

namespace Tests\Unit\DeepJsonResourceTest;

use App\Common\DeepJsonResource\DeepJsonTransformer;
use Illuminate\Http\Request;

use PHPUnit\Framework\TestCase;

class DeepJsonResourceTest extends TestCase
{

    /**
     * A basic test example.
     */
    public function test_simple_whitelist(): void
    {
        $testSubClass = new SubClassTest();
        $testSubClass->subProperty1 = 'testSubValue1';

        $testClass = new TestClass();
        $testClass->property1 = 'value1';
        $testClass->property2 = 'value2';
        $testClass->subClass = $testSubClass;

        $resultResource = new TestResourceOnlyFirstProperty($testClass);

        $resultArray = $resultResource->toArray($this->createMock(Request::class));

        $this->assertEquals(
            [
                'property1' => 'value1',
            ],
            $resultArray,
        );
    }

    /**
     * A basic test example.
     */
    public function test_simple_all_fields(): void
    {
        $testSubClass = new SubClassTest();
        $testSubClass->subProperty1 = 'testSubValue1';
        $testSubClass->subProperty2 = 'testSubValue2';
        $testSubClass->subProperty3 = null;

        $testClass = new TestClass();
        $testClass->property1 = 'value1';
        $testClass->property2 = 'value2';
        $testClass->subClass = $testSubClass;
        $testClass->dynamicProperty = 'dynamic_value';

        $resultResource = new TestResourceAllProperties($testClass);

        $resultArray = $resultResource->toArray($this->createMock(Request::class));

        $this->assertEquals(
            [
                'property1' => 'value1',
                'property2' => 'value2',
                'property3' => null,
                'property4' => null,
                'dynamicProperty' => 'dynamic_value',
                'subClass' => [
                    'subProperty1' => 'testSubValue1',
                    'subProperty2' => 'testSubValue2',
                    'subProperty3' => null,
                    'subProperty4' => null,
                ],
            ],
            $resultArray,
        );
    }

    public function test_transformer(): void {
        $testClass = new TestClass();
        $testClass->property1 = 'value1';
        $testClass->property2 = 'value2';

        $transformer = new DeepJsonTransformer([
            TestClass::class => TestResourceOnlyFirstProperty::class,
        ]);

        $resultArray = $transformer
            ->valueToSimpleType($testClass)
            ->toArray($this->createMock(Request::class));

        $this->assertEquals(
            [
                'property1' => 'value1',
            ],
            $resultArray,
        );
    }
}
