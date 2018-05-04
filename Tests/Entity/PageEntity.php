<?php

namespace Emr\CMBundle\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;
use Emr\CMBundle\Configuration\Annotations as CMS;

/**
 * Example entity
 * @ORM\Entity
 * @ORM\Table("cms_pages")
 * @CMS\Page
 */
class PageEntity extends \Emr\CMBundle\Entity\Page
{
    /**
     * @CMS\Section("example")
     */
    public $example;
}