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
     * @var Page[]|ArrayCollection
     */
    protected $pages;

    /**
     * @var Page[]
     */
    private $removedPages = [];

    /**
     * @var Page[]
     */
    private $newPages = [];

    /**
     * @var string
     * @ORM\Column(length=128, nullable=true)
     *
     */
    public $identifier;

    public function __construct()
    {
        $this->pages = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function addPage(Page $page): self
    {
        $this->pages->add($page);
        $this->newPages[] = $page;

        return $this;
    }

    public function removePage(Page $page)
    {
        $this->pages->removeElement($page);
        $this->removedPages[] = $page;
    }

    public function getRemovedPages(): array
    {
        return $this->removedPages;
    }

    public function getNewPages(): array
    {
        return $this->newPages;
    }

    public function getPages()
    {
        return $this->pages;
    }

    public function __toString()
    {
        if ($this->identifier)
            $str = $this->identifier;
        else
        {
            $str = "(#{$this->id}) ";
            if (count($this->pages) > 0)
                foreach ($this->pages as $page)
                    $str .= $page->getKey().'/'.$page->getConstant().',';
            else
                $str .= '--';
        }

        return rtrim($str, ',');
    }
}
