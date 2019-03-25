<?php

declare(strict_types=1);

namespace Mordilion\AnnotationHydrator\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class Extract
{
    /**
     * @var string
     */
    public $group;
}