<?php
declare(strict_types=1);

namespace Bot\Infrastructure\Persistence\Doctrine\Repository;

use Bot\Domain\Shared\Repository\AdvancedFilter;
use Bot\Domain\Shared\Repository\Filter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Doctrine\DBAL\Connection;

abstract class DoctrineBaseRepository
{
    protected ObjectRepository $objectRepository;

    public function __construct(
        private readonly ManagerRegistry $managerRegistry
    ) {
        $this->objectRepository = $this->getEntityManager()->getRepository($this->entityClass());
    }

    abstract protected static function entityClass(): string;
    abstract protected function getAlias(): string;


    protected function getEntityManager(): EntityManager | ObjectManager
    {
        $entityManager = $this->managerRegistry->getManager();

        if ($entityManager->isOpen()) {
            return $entityManager;
        }

        return $this->managerRegistry->resetManager();
    }


    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    protected function saveEntity(object $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    protected function removeEntity(object $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }


    protected function buildSearchQuery(
        QueryBuilder $queryBuilder,
        AdvancedFilter $filters
    ): QueryBuilder
    {

        /**
         * @var $filter Filter
         */
        foreach ($filters->getFilters() as $filter){

            if($filter->condition === AdvancedFilter::EQ){
                $queryBuilder = $this->processEqualToCondition($queryBuilder,$filter);
            }
            if($filter->condition === AdvancedFilter::NOTEQ){
                $queryBuilder = $this->processNotEqualToCondition($queryBuilder,$filter);
            }
            if($filter->condition === AdvancedFilter::GT){
                $queryBuilder = $this->processGreaterThanCondition($queryBuilder,$filter);
            }
            if($filter->condition === AdvancedFilter::LT){
                $queryBuilder = $this->processLessThanCondition($queryBuilder,$filter);
            }

        }

        return $queryBuilder;

    }

    private function processEqualToCondition(QueryBuilder $queryBuilder, Filter $filter): QueryBuilder
    {
        if($filter->hasMultipleValues === false){
            $tableColumn = sprintf(':%s',$filter->field);
            $queryBuilder->andWhere(
                $queryBuilder->expr()->eq(sprintf('%s.%s',$this->getAlias(),$filter->field), $tableColumn))
                ->setParameter(
                    $tableColumn,
                    $filter->value
                );
        }

        if($filter->hasMultipleValues === true){
            if(!empty($filter->value)){
                $queryBuilder->andWhere(
                    $queryBuilder->expr()->in(sprintf('%s.%s',$this->getAlias(),$filter->field), $filter->values));
            }
        }

        return $queryBuilder;
    }

    private function processNotEqualToCondition(QueryBuilder $queryBuilder, Filter $filter): QueryBuilder
    {
        if($filter->hasMultipleValues === false){
            $tableColumn = sprintf(':%s',$filter->field);
            $queryBuilder->andWhere(
                $queryBuilder->expr()->neq(sprintf('%s.%s',$this->getAlias(),$filter->field), $tableColumn))
                ->setParameter(
                    $tableColumn,
                    $filter->value
                );
        }

        if($filter->hasMultipleValues === true){
            if(!empty($filter->value)){
                $queryBuilder->andWhere(
                    $queryBuilder->expr()
                        ->notIn(
                            sprintf('%s.%s',$this->getAlias(),$filter->field),
                            $filter->values
                        )
                );
            }
        }

        return $queryBuilder;
    }

    private function processGreaterThanCondition(QueryBuilder $queryBuilder, Filter $filter): QueryBuilder
    {

        if(!empty($filter->value)){
            $tableColumn = sprintf(':%s',$filter->field);
            $queryBuilder->andWhere(
                $queryBuilder->expr()->gte(sprintf('%s.%s',$this->getAlias(),$filter->field), $tableColumn))
                ->setParameter(
                    $tableColumn,
                    $filter->value
                );
        }

        return $queryBuilder;
    }

    private function processLessThanCondition(QueryBuilder $queryBuilder, Filter $filter): QueryBuilder
    {
        if(!empty($filter->value)){
            $tableColumn = sprintf(':%s',$filter->field);
            $queryBuilder->andWhere(
                $queryBuilder->expr()->lte(sprintf('%s.%s',$this->getAlias(),$filter->field), $tableColumn))
                ->setParameter(
                    $tableColumn,
                    $filter->value
                );
        }

        return $queryBuilder;
    }

}