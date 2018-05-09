<?php

namespace Emr\CMBundle\Configuration\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Admin
{
    /** @var array */
    public $settings = [];
}