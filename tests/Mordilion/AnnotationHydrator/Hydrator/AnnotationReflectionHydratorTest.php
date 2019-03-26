<?php

declare(strict_types=1);

use Mordilion\AnnotationHydrator\Annotation;
use Mordilion\AnnotationHydrator\Hydrator\AnnotationReflectionHydrator;
use PHPUnit\Framework\TestCase;

class AnnotationReflectionHydratorTest extends TestCase
{
    public function testExtractReturnsOnlyGroup1Properties()
    {
        $object = new ExampleClass('name 1', 'secret 1');
        $hydrator = new AnnotationReflectionHydrator();

        $hydrator->setGroup('group_1');

        $data = $hydrator->extract($object);

        $this->assertCount(1, $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertEquals('name 1', $data['name']);
    }

    public function testExtractReturnsOnlyGroup2Properties()
    {
        $object = new ExampleClass('name 2', 'secret 2');
        $hydrator = new AnnotationReflectionHydrator();

        $hydrator->setGroup('group_2');

        $data = $hydrator->extract($object);

        $this->assertCount(2, $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('secret', $data);
        $this->assertEquals('name 2', $data['name']);
        $this->assertEquals('secret 2', $data['secret']);
    }

    public function testHydrateSetsOnlyGroup1Properties()
    {
        $data = ['name' => 'name 1', 'secret' => 'secret 1'];

        $object = new ExampleClass(null, null);
        $hydrator = new AnnotationReflectionHydrator();

        $hydrator->setGroup('group_1');

        $hydrator->hydrate($data, $object);

        $this->assertEquals('secret 1', $object->secret);
        $this->assertNull($object->name);
    }

    public function testHydrateSetsOnlyGroup2Properties()
    {
        $data = ['name' => 'name 2', 'secret' => 'secret 2'];

        $object = new ExampleClass(null, null);
        $hydrator = new AnnotationReflectionHydrator();

        $hydrator->setGroup('group_2');

        $hydrator->hydrate($data, $object);

        $this->assertEquals('name 2', $object->name);
        $this->assertEquals('secret 2', $object->secret);
    }
}

// ---

class ExampleClass
{
    /**
     * @Annotation\Extract(groups={"group_1", "group_2"})
     * @Annotation\Hydrate(groups={"group_2"})
     */
    public $name;

    /**
     * @Annotation\Extract(groups={"group_2"})
     * @Annotation\Hydrate(groups={"group_1", "group_2"})
     */
    public $secret;


    public function __construct($name, $secret)
    {
        $this->name = $name;
        $this->secret = $secret;
    }
}