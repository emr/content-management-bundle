<?php

namespace Emr\CMBundle\Configuration;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;
use Emr\CMBundle\Configuration\Annotations;
use Emr\CMBundle\Exception\NoNameForSectionException;
use Emr\CMBundle\Exception\SectionNotFoundException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Illuminate\Support\Str;

class AnnotationEntityConfig extends EntityConfig
{
    /**
     * @var Finder
     */
    private $finder;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var array
     */
    private $annotationClasses = [
        self::PAGE => Annotations\Page::class,
        self::USER => Annotations\User::class,
        self::CONSTANT => Annotations\Constant::class,
        self::LOCALIZED_CONSTANT => Annotations\LocalizedConstant::class,
        'admin' => Annotations\Admin::class,
        'section' => Annotations\Section::class,
        'fields' => Annotations\Field::class,
    ];

    /**
     * @var array
     */
    private $config = [
        self::PAGE => null,
        self::CONSTANT => null,
        self::LOCALIZED_CONSTANT => null,
        'admin' => [],
        'section' => [],
    ];

    /**
     * @var boolean
     */
    private $done = false;

    /**
//     * @param Reader $reader
     * @param string $path
     * @param string $namespace
     */
    public function __construct(/*Reader $reader,*/ $path, $namespace)
    {
        $this->finder = new Finder();
        $this->reader = new AnnotationReader();
        AnnotationRegistry::registerUniqueLoader('class_exists');
        $this->path = $path;
        $this->namespace = $namespace;
    }

    private function makeClassConfiguration()
    {
        /** @var $file SplFileInfo */
        foreach ($this->finder->files()->in($this->path) as $file)
        {
            $relativePath = $file->getRelativePath();
            $class = new \ReflectionClass(
                $this->namespace . ($relativePath ? '\\'.str_replace('/', '\\', $relativePath) : null) . '\\' . $file->getBasename('.php')
            );

            foreach ($this->annotationClasses as $annotationClass)
                if ($classAnnotation = $this->reader->getClassAnnotation($class, $annotationClass))
                    $this->config[array_flip($this->annotationClasses)[$annotationClass]][$class->getName()] = (array)$classAnnotation;
        }

        $this->done = true;
    }

    public function getClass(string $for): string
    {
        if (!$this->done)
            $this->makeClassConfiguration();

        return key($this->config[$for]);
    }

    /**
     * @todo class ile property işleştir.
     */
    public function getSections(): array
    {
        if (!$this->done)
            $this->makeClassConfiguration();

        $sections = [];
        $sectionClasses = [];

        foreach ($this->config['section'] as $class => $config)
            $sectionClasses[$config['name']] = [
                'class' => $class,
                'label' => $config['label'],
                'admin' => $config['admin'],
            ];

        $class = new \ReflectionClass($this->getClass(self::PAGE));

        foreach ($class->getProperties() as $property)
        {
            if ($annotation = $this->reader->getPropertyAnnotation($property, $this->annotationClasses['section']))
            {
                if (!$annotation->name)
//                    throw new NoNameForSectionException("The name property not specified in \"{$class->getName()}#{$property->getName()}\"");
                    $annotation->name = $property->getName();

                if (isset($sectionClasses[$annotation->name]))
                {
                    $sections[$annotation->name] = $sectionClasses[$annotation->name];
                    $sections[$annotation->name]['name'] = $annotation->name;
                    $sections[$annotation->name]['property'] = $property->getName();
                }
                else
                    throw new SectionNotFoundException("No class found for section \"{$annotation->name}\"");

            }
        }

        return $sections;
    }

    public function getSectionClasses(): array
    {
        $sections = [];

        foreach ($this->config['section'] as $class => $config)
            $sections[$config['name']] = $class;

        return $sections;
    }

    public function getFields(string $for, callable $filter = null): array
    {
        $fields = [];
        try {
            $class = new \ReflectionClass($for);
        } catch (\ReflectionException $e) {
            $class = new \ReflectionClass($this->getClass($for));
        }
        foreach ($class->getProperties() as $property)
        {
            if ($annotation = $this->reader->getPropertyAnnotation($property, $this->annotationClasses['fields']))
            {
                while (isset($fields[$annotation->position]))
                    $annotation->position += 1;

                foreach ((array)$annotation as $key => $value)
                {
                    if ('extra' === $key)
                    {
                        foreach ($value as $_key => $_value)
                            if (null !== $_value)
                                $fields[$annotation->position][Str::snake($_key)] = $_value;
                    }
                    if (null !== $value)
                        $fields[$annotation->position][Str::snake($key)] = $value;
                }

                $fields[$annotation->position]['property'] = $fields[$annotation->position]['property'] ?? $property->getName();
            }
        }

        ksort($fields);
        return $filter ? array_filter(array_values($fields), $filter) : array_values($fields);
    }

    public function getAdmins(): array
    {
        if (!$this->done)
            $this->makeClassConfiguration();

        $admins = [];

        foreach ($this->config['admin'] as $class => $config)
            $admins[$class] = array_merge(['class' => $class], $this->getAdmin($class));

        return $admins;
    }

    public function getAdmin(string $class): array
    {
        if (!$this->done)
            $this->makeClassConfiguration();

        return isset($this->config['admin'][$class]) ? $this->config['admin'][$class]['settings'] : [];
    }
}