<?php

namespace Emr\CMBundle\Configuration;

abstract class EntityConfig
{
    public const PAGE               = 'page';
    public const USER               = 'user';
    public const CONSTANT           = 'constant';
    public const LOCALIZED_CONSTANT = 'localized_constant';

    /**
     * Get class for a key
     * @param string $for
     * @return string
     */
    abstract public function getClass(string $for): string;

    /**
     * Get fields for a class
     * @param string $for       Class or one of self constants
     * @param callable $filter  Filter to be parameter of array_filter
     * @return array
     */
    abstract public function getFields(string $for, callable $filter = null): array;

    /**
     * Get CMS sections
     * @return array
     */
    abstract public function getSections(): array;

    /**
     * Get classes defined as admin
     * @return array
     */
    abstract public function getAdminClasses(): array;

    /**
     * Get classes defined as section
     * @return array
     */
    public function getSectionClasses(): array
    {
        return array_map(
            function($section) {
                return $section['class'];
            },
            $this->getSections()
        );
    }

    public function getSection(string $prop): array
    {
        $sections = $this->getSections();

        if (!($section = $sections[$prop] ?? null))
            foreach ($sections as $item)
                if ($item['class'] == $prop)
                    $section = $item;

        return $section;
    }

    public function isSection(string $class): bool
    {
        foreach ($this->getSections() as $section)
            if ($class == $section['class']) return true;

        return false;
    }
}