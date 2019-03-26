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

namespace Mordilion\AnnotationHydrator;

use Doctrine\Common\Annotations\AnnotationReader;

/**
 * @author Henning Huncke <mordilion@gmx.de>
 */
trait AnnotationReaderAwareTrait
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;


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
