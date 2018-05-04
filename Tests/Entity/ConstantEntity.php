<?php

namespace Emr\CMBundle\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;
use Emr\CMBundle\Configuration\Annotations as CMS;

/**
 * Example entity
 * @ORM\Entity
 * @ORM\Table("cms_general_constants")
 * @CMS\Constant
 */
class ConstantEntity extends \Emr\CMBundle\Entity\Constant
{
    /**
     * @var string
     * @ORM\Column(length=16)
     * @CMS\Field
     */
    public $googleTrackId = 'UA-00000000-1';
}