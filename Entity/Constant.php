<?php

namespace Emr\CMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
abstract class Constant
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\Column(type="smallint")
     * @ORM\GeneratedValue()
     */
    protected $id;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function __toString()
    {
        return 'Constant';
    }

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
//    }
}