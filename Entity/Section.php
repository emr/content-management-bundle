<?php

namespace Emr\CMBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
abstract class Section
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="smallint")
     */
    protected $id;

    /**
     * @var Page[]
     */
    protected $page;

    /**
     * @var string
     * @ORM\Column(length=128, nullable=true)
     */
    public $identifier;

    public function __construct()
    {
        $this->page = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function addPage(Page $page): self
    {
//        $page->{EntityConfig::getConfig('section', static::class, 'name')} = $this;
        $this->page->add($page);

        return $this;
    }

    public function removePage(Page $page)
    {
        $this->page->removeElement($page);
    }

    public function getPage()
    {
        return $this->page;
    }

    public function __toString()
    {
        $str = "(#{$this->id}) ";

        if ($this->identifier)
            $str .= $this->identifier;
        else
            if ($this->page)
                foreach ($this->page as $page)
                    $str .= $page->getKey().'/'.$page->getConstant()->getLocale().',';
            else
                $str .= 'orphan';

        return rtrim($str, ',');
    }
}
