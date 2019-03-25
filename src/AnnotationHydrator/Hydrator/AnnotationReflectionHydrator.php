<?php

declare(strict_types=1);

namespace Mordilion\AnnotationHydrator\Hydrator;

use Doctrine\Common\Annotations\AnnotationReader;
use Mordilion\AnnotationHydrator\Annotation\Extract;
use Mordilion\AnnotationHydrator\Annotation\Hydrate;
use Zend\Hydrator\ReflectionHydrator;

/**
 * @author Henning Huncke <mordilion@gmx.de>
 */
class AnnotationReflectionHydrator extends ReflectionHydrator
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;

    /**
     * @var string|null
     */
    private $group;


    /**
     * AnnotationHydrator constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param object $object
     *
     * @return array
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function extract(object $object): array
    {
        $result = [];
        $reflProperties = self::getReflProperties($object);

        $annotationReader = $this->getAnnotationReader();

        foreach ($reflProperties as $property) {
            $annotations = $annotationReader->getPropertyAnnotations($property);
            $propertyName = $this->extractName($property->getName(), $object);

            if ((!$annotation = $this->getAnnotation($annotations, Extract::class))
                || ($this->group !== null && $annotation->group !== $this->group)
                || (!$this->getCompositeFilter()->filter($propertyName))
            ) {
                continue;
            }

            $value = $property->getValue($object);
            $result[$propertyName] = $this->extractValue($propertyName, $value, $object);
        }

        return $result;
    }

    /**
     * @param array  $data
     * @param object $object
     *
     * @return object|void|\Zend\Hydrator\object
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function hydrate(array $data, object $object)
    {
        $reflProperties = self::getReflProperties($object);

        $annotationReader = $this->getAnnotationReader();

        foreach ($data as $key => $value) {
            $name = $this->hydrateName($key, $data);
            $property = $reflProperties[$name] ?? null;

            if ($property instanceof \ReflectionProperty) {
                $annotations = $annotationReader->getPropertyAnnotations($property);

                if ((!$annotation = $this->getAnnotation($annotations, Hydrate::class))
                    || ($this->group !== null && $annotation->group !== $this->group)
                ) {
                    continue;
                }

                $reflProperties[$name]->setValue($object, $this->hydrateValue($name, $value, $data));
            }
        }

        return $object;
    }

    /**
     * @return AnnotationReader
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function getAnnotationReader(): AnnotationReader
    {
        if (!$this->annotationReader instanceof AnnotationReader) {
            $this->annotationReader = new AnnotationReader();
        }

        return $this->annotationReader;
    }

    /**
     * @param AnnotationReader $annotationReader
     */
    public function setAnnotationReader(AnnotationReader $annotationReader): void
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @return string
     */
    public function getGroup(): string
    {
        return $this->group;
    }

    /**
     * @param string|null $group
     */
    public function setGroup(?string $group)
    {
        $this->group = $group;
    }

    /**
     * @param array  $annotations
     * @param string $class
     *
     * @return object|null
     */
    protected function getAnnotation(array $annotations, string $class): ?object
    {
        foreach ($annotations as $annotation) {
            if ($annotation instanceof $class) {
                return $annotation;
            }
        }

        return null;
    }
}
