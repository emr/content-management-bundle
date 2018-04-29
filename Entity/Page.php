<?php

namespace Emr\CMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
abstract class Page implements \ArrayAccess
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\Column(type="smallint")
     * @ORM\GeneratedValue()
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="key", type="smallint", length=32, nullable=false)
     */
    protected $page;

    /**
     * Will be configured as many to one
     * @var LocalizedConstant
     */
    protected $constant;

    /**
     * @var array
     */
    protected $sections;

//    public function loadMetadata(ORM\ClassMetadata $metadata)
//    {
//        $builder = new ORM\Builder\ClassMetadataBuilder($metadata);
//
//        $builder->setMappedSuperClass();
//
//        $builder->createField('id', 'smallint')
//            ->makePrimaryKey()
//            ->generatedValue()
//        ->build();
//
//        $builder->createField('page', 'string')
//            ->nullable(false)
//        ->build();
//
//        $builder->createField('name', 'string')
//            ->length(32)
//        ->build();
//
//        $builder->createManyToOne('constant', EntityConfig::getConfig('localized_constant'))
//            ->addJoinColumn('locale', 'locale')
//        ->build();
//    }

    public function getId()
    {
        return $this->id;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->sections);
    }

    public function offsetGet($offset)
    {
        return $this->sections[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->sections[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->sections[$offset]);
    }

    public function __call($name, $arguments)
    {
        switch ($name)
        {
            case 'settings': return $this->constant; break;
            case 'locale': return $this->constant->getLocale(); break;
            default: return $this->$name;
        }
    }

    public function __get($name)
    {
        return $this->__call($name, []);
    }

    public function __toString()
    {
        return (string) $this->page . ' / ' . $this->constant;
    }
}