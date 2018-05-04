<?php

namespace Emr\CMBundle\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\DataTransformerInterface;

class HiddenEntityType extends AbstractType
{
    /** @var DataTransformerInterface $transformer */
    private $transformer;

    public function __construct(DataTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // attach the specified model transformer for this entity list field
        // this will convert data between object and string formats
        $builder->addModelTransformer($this->transformer);
    }

    public function getParent()
    {
        return 'hidden';
    }

    public function getName()
    {
        return 'entityhidden';
    }
}
