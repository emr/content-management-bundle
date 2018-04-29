<?php

namespace Emr\CMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
abstract class LocalizedConstant
{
    /**
     * Will be configured as many to one
     * @var GeneralConstant
     */
    protected $constant;

    /**
     * @var string
     * @ORM\Column(type="string", length=8)
     * @ORM\Id()
     */
    protected $locale; #key

    public function getLocale()
    {
        return $this->locale;
    }

//    public function loadMetadata(ORM\ClassMetadata $metadata)
//    {
//        $builder = new ORM\Builder\ClassMetadataBuilder($metadata);
//
//        $builder->setMappedSuperClass();
//
//        $builder->createField('locale', 'string')
//            ->makePrimaryKey()
//            ->length(8)
//        ->build();
//
////        $builder->createManyToOne('constant', EntityConfig::getConfig('general_constant'))
////            ->fetchEager()
//////            ->addJoinColumn('constant_id', 'locale')
////        ->build();
//    }

    public function __toString()
    {
        return \Symfony\Component\Intl\Intl::getLanguageBundle()->getLanguageName($this->locale);
    }

    public function __get($name)
    {
        return $this->constant->$name;
    }
}