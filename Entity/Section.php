<?php

namespace Emr\CMBundle\Entity;

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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CMS\Page", mappedBy="example")
     */
    protected $page;

    /**
     * @var string
     * @ORM\Column(length=128, nullable=true)
     */
    public $identifier;

    public function getId(): integer
    {
        return $this->id;
    }

    public function setPage(Page $page)
    {
//        $page->{EntityConfig::getConfig('section', static::class, 'name')} = $this;
        $this->page = $page;
    }

    public function getPage(): Page
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
                $str .= $this->page->page.'/'.$this->page->constant->locale.',';
            else
                $str .= 'orphan';

        return rtrim($str, ',');
    }
}
