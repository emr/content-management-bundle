<?php

namespace Emr\CMBundle\Configuration;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;
use Emr\CMBundle\Configuration\Annotations;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

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
        'admin' => null,
        'section' => null,
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
                    $this->config[array_flip($this->annotationClasses)[$annotationClass]][$class->getName()]['class'] = (array)$classAnnotation;
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

        foreach ($this->config['section'] as $class => $config)
            $sections[$config['class']['name']] = [
                'class' => $class,
                'property' => $config['class']['name'],
                'label' => $config['class']['label'],
                'admin' => $config['class']['admin'],
            ];

        return $sections;
    }

    public function getSectionClasses(): array
    {
        $sections = [];

        foreach ($this->config['section'] as $class => $config)
            $sections[$config['class']['name']] = $class;

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

                $fields[$annotation->position] = array_filter([
                    'property' => $property->getName(),
                    'type' => $annotation->type,
                    'label' => $annotation->label,
                    'format' => $annotation->format,
                    'help' => $annotation->help,
                    'field_type' => $annotation->fieldType,
                    'data_type' => $annotation->dataType,
                    'virtual' => $annotation->virtual,
                    'sortable' => $annotation->sortable,
                    'template' => $annotation->template,
                    'type_options' => $annotation->typeOptions,
                    'form_group' => $annotation->formGroup,
                    'css_class' => $annotation->cssClass,
                    'role_require' => $annotation->roleRequire,
                    'actions' => $annotation->actions,
                ]);
            }
        }

        ksort($fields);
        return $filter ? array_filter(array_values($fields), $filter) : array_values($fields);
    }

    public function getAdminClasses(): array
    {
        if (!$this->done)
            $this->makeClassConfiguration();

        return array_map(
            function($c) {
                return $c['class'];
            },
            $this->config['admin']
        );
    }
}