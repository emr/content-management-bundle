<?php

namespace Emr\CMBundle\Configuration;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\PropertyAccess\Exception\NoSuchIndexException;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class AnnotationEntityConfig extends EntityConfig
{
    /**
     * @var Finder
     */
    private $finder;

//    /**
//     * @var PropertyAccessor
//     */
//    private $accessor;

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
    private $classes;

    /**
     * @var array
     */
    private $config = [
        'page_class' => null,
        'general_constant' => null,
        'localized_constant' => null,
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
     * @param string[] $classes
     */
    public function __construct(/*Reader $reader,*/ $path, $namespace, $classes)
    {
        $this->finder = new Finder();
        $this->reader = new AnnotationReader();
        AnnotationRegistry::registerUniqueLoader('class_exists');
//        $this->accessor = new PropertyAccessor();
        $this->path = $path;
        $this->namespace = $namespace;
        $this->classes = $classes;
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

            foreach ($this->classes as $annotationClass)
                if ($classAnnotation = $this->reader->getClassAnnotation($class, $annotationClass))
                    $this->config[array_flip($this->classes)[$annotationClass]][$class->getName()]['class'] = $classAnnotation;
        }

        $this->done = true;
    }

    public function getPageClass(): string
    {
        if (!$this->done)
            $this->makeClassConfiguration();

        return key($this->config['page_class']);
    }

    public function getGeneralConstantClass(): string
    {
        if (!$this->done)
            $this->makeClassConfiguration();

        return key($this->config['general_constant']);
    }

    public function getLocalizedConstantClass(): string
    {
        if (!$this->done)
            $this->makeClassConfiguration();

        return key($this->config['localized_constant']);
    }

    /**
     * @todo class ile property işleştir.
     */
    public function getSections(): array
    {
        if (!$this->done)
            $this->makeClassConfiguration();

//        if ($this->config['section'])
//            return $this->config['section'];
//
//        foreach ((new \ReflectionClass($this->getPageClass()))->getProperties() as $property)
//            /** @var Section $annotation */
//            if ($annotation = $this->reader->getPropertyAnnotation($property, $this->classes['section']))
//                $this->config['section'][] = [
//                    'property' => $property->getName(),
//                    'class' => $annotation->class
//                ];
//
//        return $this->config['section'];

        $sections = [];

        foreach ($this->config['section'] as $class => $annotation)
            $sections[] = [
                'class' => $class,
                'property' => $annotation['class']->name
            ];


        return $sections;
    }

//    public function getConfig($key, ...$calls)
//    {
//        if (!$this->done)
//            $this->makeClassConfiguration();
//
//        $try = [];
//
//        $getFirst = $calls[0] ?? true;
//
//        if (true === $getFirst)
//        {
//            array_push($try, "[{$key}][0]");
//        }
//        elseif (false === $getFirst)
//        {
//            array_push($try, "[{$key}]");
//        }
//        else
//        {
//            $try = [];
//            $call = "[{$key}]";
//            for ($i = 0; $i < count($calls); $i++)
//            {
//                array_push($try, "{$call}.{$calls[$i]}");
//                array_push($try, "{$call}[{$calls[$i]}]");
//
//                $call = $try[count($try) - 1];
//            }
//        }
//
//        while ($call = array_pop($try))
//        {
//            try {
//                return $this->accessor->getValue($this->config, $call);
//            } catch (NoSuchPropertyException $e) {
//            } catch (NoSuchIndexException $e) {
//            }
//        }
//    }
}