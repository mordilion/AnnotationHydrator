<?php

declare(strict_types=1);

/**
 * This file is part of the Mordilion\AnnotationHydrator package.
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 *
 * @copyright (c) Henning Huncke - <mordilion@gmx.de>
 */

namespace Mordilion\AnnotationHydrator\Hydrator;

use Mordilion\AnnotationHydrator\Annotation\Extract;
use Mordilion\AnnotationHydrator\Annotation\Hydrate;
use Mordilion\AnnotationHydrator\AnnotationReaderAwareInterface;
use Mordilion\AnnotationHydrator\AnnotationReaderAwareTrait;
use Zend\Hydrator\ReflectionHydrator;

/**
 * @author Henning Huncke <mordilion@gmx.de>
 */
class AnnotationReflectionHydrator extends ReflectionHydrator implements AnnotationReaderAwareInterface
{
    use AnnotationReaderAwareTrait;


    /**
     * @var string|null
     */
    private $group;


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
            $annotation = $this->getAnnotation($annotations, Extract::class);

            if ((!$annotation instanceof Extract)
                || ($this->group !== null && !in_array($this->group, (array)$annotation->groups, false))
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
                $annotation = $this->getAnnotation($annotations, Hydrate::class);

                if ((!$annotation instanceof Hydrate)
                    || ($this->group !== null && !in_array($this->group, (array)$annotation->groups, false))
                ) {
                    continue;
                }

                $property->setValue($object, $this->hydrateValue($name, $value, $data));
            }
        }

        return $object;
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
}
