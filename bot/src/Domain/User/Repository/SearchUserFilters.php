<?php
declare(strict_types=1);

namespace Bot\Domain\User\Repository;


use Bot\Domain\Shared\Repository\AdvancedFilter;

class SearchUserFilters extends AdvancedFilter
{
    protected function allowedFields(): array
    {
        return [
            'name',
            'lastName',
            'location',
            'age',
            'category',
            'active',
            'createdAt',
            'updatedAt'
        ];
    }

    protected function allowedDateTimeFields(): array
    {
        return [
            'createdAt',
            'updatedAt'
        ];
    }

    protected function allowedBooleanFields(): array
    {
        return ['active'];
    }

    protected function allowedRangeFields(): array
    {
       return [
           'createdAt',
           'updatedAt',
           'age'
       ];
    }


    public function createFilters(array $query): void
    {
        $this->build($query);
    }

}