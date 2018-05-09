<?php

namespace Emr\CMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
abstract class Page
{
    /**
     * @var integer
     * @ORM\Id()
     * @ORM\Column(type="smallint")
     * @ORM\GeneratedValue()
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="_key", length=32)
     */
    protected $key;

    /**
     * @var string
     * @ORM\Column()
     */
    protected $name;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $menuView = true;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $menuPosition = 10;

    /**
     * Will be configured as many to one
     * @var LocalizedConstant
     */
    protected $constant;

    public function getId()
    {
        return $this->id;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey(string $key)
    {
        $this->key = $key;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getMenuView()
    {
        return $this->menuView;
    }

    public function setMenuView(bool $menuView)
    {
        $this->menuView = $menuView;
    }

    public function getMenuPosition()
    {
        return $this->menuPosition;
    }

    public function setMenuPosition($menuPosition)
    {
        $this->menuPosition = $menuPosition;
    }

    public function setConstant(LocalizedConstant $constant)
    {
        $this->constant = $constant;
    }

    public function getConstant(): ?LocalizedConstant
    {
        return $this->constant;
    }

    public function getSettings()
    {
        $this->getConstant();
    }

    public function setSections($sections)
    {
        foreach ($sections as $section)
            $this->{$section['property']} = (new $section['class']())->addPage($this);
    }

    public function getSections()
    {
        return [];
    }

    public function getLocale()
    {
        return $this->getConstant()->getLocale();
    }

    public function __toString()
    {
        return (string) $this->key . ' / ' . $this->constant;
    }
}