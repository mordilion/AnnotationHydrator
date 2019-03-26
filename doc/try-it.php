<?php

declare(strict_types=1);

$autoloader = require __DIR__ . '/../vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationRegistry;
use Mordilion\AnnotationHydrator\Hydrator\AnnotationReflectionHydrator;
use Mordilion\AnnotationHydrator\Annotation;

AnnotationRegistry::registerLoader('class_exists');


class TestClass
{
    /**
     * @Annotation\Extract(groups={"test1"})
     * @Annotation\Hydrate(groups={"test1"})
     */
    public $name = 'name';

    /**
     * @Annotation\Extract(groups={"test2"})
     * @Annotation\Hydrate(groups={"test2"})
     */
    public $title = 'title';

    public function __construct($name, $title)
    {
        $this->name = $name;
        $this->title = $title;
    }
}



$hydrator = new AnnotationReflectionHydrator();
$hydrator->setGroup('test1');

$obj1 = new TestClass('name 1', 'title 1');
$data = $hydrator->extract($obj1);
var_dump($data);

$obj2 = new TestClass('name 2', 'title 2');
$hydrator->hydrate($data, $obj2);
var_dump($obj2);
