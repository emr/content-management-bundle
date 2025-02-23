<?php

namespace Emr\CMBundle\Tests\Entity\Section;

use Doctrine\ORM\Mapping as ORM;
use Emr\CMBundle\Configuration\Annotations as CMS;
use Emr\CMBundle\Entity\Section;

/**
 * @ORM\Entity
 * @ORM\Table("cms_example_sections")
 * @CMS\Section("example")
 */
class ExampleSection extends Section
{
}