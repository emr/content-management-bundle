<?php

namespace Emr\CMBundle\Configuration\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Field
{
    /** @var array */
    public $extra = [];
    /** @var string */
    public $type;
    /** @var string */
    public $property;
    /** @var mixed */
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
    /** @var string */
    public $basePath;
    /** @var integer */
    public $position = 100;
    /** @var string */
    public $roleRequire;
    /** @var array */
    public $actions = ['form', 'list', 'new', 'edit', 'show'];
    /** @var array */
    public $disabledActions = [];
}