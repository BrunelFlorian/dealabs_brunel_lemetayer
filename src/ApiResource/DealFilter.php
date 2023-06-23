<?php

namespace App\Filter;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\AbstractContextAwareFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use Doctrine\ORM\QueryBuilder;

class DealFilter extends AbstractContextAwareFilter
{
    protected function filterProperty(string $property, $value, QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($property !== 'recent') {
            return;
        }

        $expirationDate = new \DateTimeImmutable('-1 week');
        $queryBuilder->andWhere('o.createdAt > :expirationDate')
            ->setParameter('expirationDate', $expirationDate);
    }

    public function getDescription(string $resourceClass): array
    {
        return [
            'recent' => [
                'property' => 'recent',
                'type' => 'boolean',
                'required' => false,
                'description' => 'Filter deals created within the last week',
            ],
        ];
    }
}
