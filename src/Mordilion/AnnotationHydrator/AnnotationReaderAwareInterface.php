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
interface AnnotationReaderAwareInterface
{
    /**
     * @return AnnotationReader
     */
    public function getAnnotationReader(): AnnotationReader;

    /**
     * @param AnnotationReader $annotationReader
     */
    public function setAnnotationReader(AnnotationReader $annotationReader): void;
}
