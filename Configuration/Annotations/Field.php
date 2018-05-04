<?php

namespace Emr\CMBundle\Configuration\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Field
{
    /** @var string */
    public $type;
    /** @var string */
    public $label;
    /** @var string */
    public $format;
    /** @var string */
    public $help;
    /** @var string */
    public $fieldType;
    /** @var string */
    public $dataType;
    /** @var bool */
    public $virtual;
    /** @var bool */
    public $sortable;
    /** @var string */
    public $template;
    /** @var array */
    public $typeOptions;
    /** @var string */
    public $formGroup;
    /** @var string */
    public $cssClass;
    /** @var integer */
    public $position = 100;
    /** @var string */
    public $roleRequire;
    /** @var array */
    public $actions = ['new', 'edit'];
}