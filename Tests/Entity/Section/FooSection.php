<?php

namespace Emr\CMBundle\Tests\Entity\Section;

use Doctrine\ORM\Mapping as ORM;
use Emr\CMBundle\Configuration\Annotations as CMS;
use Emr\CMBundle\Entity\Section;

/**
 * @ORM\Entity
 * @ORM\Table("cms_foo_sections")
 * @CMS\Section(name="foo", label="Foo)
 */
class FooSection extends Section
{
}