<?php

namespace App\Service;

use App\Annotation\HasLazyCollection;
use App\Annotation\LazyCollection as LazyCollectionAnnotation;
use App\Collection\LazyCollection;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;

class LazyCollectionService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function fillLazyCollectionInEntity($entity)
    {
        $ar = new AnnotationReader();
        $reflectionClass = new \ReflectionClass($entity);

        if (empty($ar->getClassAnnotation($reflectionClass, HasLazyCollection::class))) {
            return;
        }

        foreach ($reflectionClass->getProperties() as $property) {
            if (null === $annotation = $ar->getPropertyAnnotation($property, LazyCollectionAnnotation::class)) {
                continue;
            }

            $lazyCollection = new LazyCollection(
                $this->em,
                $entity,
                $annotation->targetEntity,
                $annotation->targetColumnName
            );

            $property->setAccessible(true);
            $property->setValue(
                $entity,
                $lazyCollection
            );
        }
    }
}