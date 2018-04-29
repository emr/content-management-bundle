<?php

namespace Emr\CMBundle\Configuration\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"CLASS", "PROPERTY"})
 */
class Section
{
    /**
     * @var string
     */
    public $name;
}