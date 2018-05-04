<?php

namespace Emr\CMBundle\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;
use Emr\CMBundle\Configuration\Annotations as CMS;

/**
 * Example entity
 * @ORM\Entity
 * @ORM\Table("cms_localized_constants")
 * @CMS\LocalizedConstant
 */
class LocalizedConstantEntity extends \Emr\CMBundle\Entity\LocalizedConstant
{
    /**
     * @var string
     * @ORM\Column(length=64, nullable=true)
     * @CMS\Field
     */
    public $name;

    /**
     * @var string
     * @ORM\Column(length=255, nullable=true)
     * @CMS\Field
     */
    public $copyright;

    /**
     * @var string
     * @ORM\Column(length=255, nullable=true)
     * @CMS\Field()
     */
    public $contactAddress;

    /**
     * @var string
     * @ORM\Column(length=64, nullable=true)
     * @CMS\Field()
     */
    public $contactEmail;

    /**
     * @var string[]
     * @ORM\Column(type="json_array", nullable=true)
     * @CMS\Field()
     */
    public $accounts = [
        'facebook'  => '',
        'twitter'   => '',
        'instagram' => '',
        'linkedin'  => '',
        'pinterest' => '',
    ];
}