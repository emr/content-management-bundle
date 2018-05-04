<?php

namespace Emr\CMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Emr\CMBundle\Configuration\Annotations as CMS;

/**
 * @ORM\MappedSuperclass()
 */
abstract class LocalizedConstant
{
    /**
     * @var string
     * @ORM\Column(type="string", length=8)
     * @ORM\Id()
     * @CMS\Field("locale", position=0, roleRequire="ROLE_ADMIN")
     */
    protected $locale; #key

    /**
     * Will be configured as many to one
     * @var Constant
     */
    protected $constant;

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale)
    {
        $this->locale = $locale;
    }

    public function getConstant(): ?Constant
    {
        return $this->constant;
    }

    public function setConstant(Constant $constant)
    {
        $this->constant = $constant;
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