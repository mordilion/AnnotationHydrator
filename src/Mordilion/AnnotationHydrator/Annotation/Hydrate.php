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

namespace Mordilion\AnnotationHydrator\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 *
 * @author Henning Huncke <mordilion@gmx.de>
 */
class Hydrate
{
    /**
     * @var array
     */
    public $groups;
}