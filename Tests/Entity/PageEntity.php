<?php

namespace Emr\CMBundle\Tests\Entity;

use Emr\CMBundle\Configuration\Annotations as CMS;
use Emr\CMBundle\Entity\Page;

/**
 * @CMS\PageClass()
 */
class PageEntity extends Page
{
    /**
     * @CMS\Section("example")
     */
    public $example;
}