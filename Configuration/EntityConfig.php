<?php

namespace Emr\CMBundle\Configuration;

use Doctrine\Common\Annotations\Reader;
use Emr\CMBundle\Annotations\PageClass;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\PropertyAccess\Exception\NoSuchIndexException;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccessor;

abstract class EntityConfig
{
    public const CONFIG_SECTION             = 'config.section';
    public const CONFIG_PAGE_CLASS          = 'config.page_class';
    public const CONFIG_GENERAL_CONSTANT    = 'config.general_constant';
    public const CONFIG_LOCALIZED_CONSTANT  = 'config.localized_constant';

    abstract public function getPageClass(): string;
    abstract public function getGeneralConstantClass(): string;
    abstract public function getLocalizedConstantClass(): string;
    abstract public function getSections(): array;

    public function isPageClass(string $class): bool
    {
        return $class == $this->getPageClass();
    }

    public function isGeneralConstantClass(string $class): bool
    {
        return $class == $this->getGeneralConstantClass();
    }

    public function isLocalizedConstantClass(string $class): bool
    {
        return $class == $this->getLocalizedConstantClass();
    }

    public function isSection(string $class): bool
    {
        foreach ($this->getSections() as $section)
            if ($class == $section['class']) return true;

        return false;
    }
}