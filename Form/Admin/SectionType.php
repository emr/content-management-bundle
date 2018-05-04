<?php

namespace Emr\CMBundle\Form\Admin;

use Emr\CMBundle\Configuration\EntityConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionType extends AbstractType
{
    /**
     * @var EntityConfig
     */
    private $config;

    /**
     * @param EntityConfig $config
     */
    public function __construct(EntityConfig $config)
    {
        $this->config = $config;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function($props) {
                return $props;
            },
            function($props) {
                return array_map(
                    function($prop) {
                        return $this->config->getSection($prop);
                    },
                    $props
                );
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $choices = [];
        foreach ($this->config->getSections() as $section)
            $choices[$section['label'] ?: ucfirst($section['property'])] = $section['property'];

        $resolver->setDefaults([
            'choices' => $choices,
            'multiple' => true,
//            'choice_attr' => function ($key, $val, $index) {
//                return [];
//            }
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}