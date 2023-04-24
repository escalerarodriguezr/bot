<?php

namespace Bot\Domain\Shared\Repository;

use Assert\Assertion;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

abstract class AdvancedFilter
{

    const DATE_FORMAT = 'Y-m-d H:i:s';
    const MULTIPLE_VALUE_PARAMS_SEPARATOR = '-|-';

    const EQ = 'EqualTo';
    const NOTEQ = 'NotEqualTo';
    const GT = 'GreaterThan';
    const LT = 'LessThan';

    const allowedConditions =[
        self::EQ,
        self::NOTEQ,
        self::GT,
        self::LT
    ];

    protected array $filters;

    public function __construct()
    {
        $this->filters = [];
    }

    abstract protected function allowedFields(): array;
    abstract protected function allowedDateTimeFields(): array;
    abstract protected function allowedBooleanFields(): array;
    abstract protected function allowedRangeFields(): array;

    protected function build(array $query): void
    {
        foreach ($query as $fieldAndCondition => $value){
            $this->processFieldConditionAndValue($fieldAndCondition,$value);
        }
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    private function processFieldConditionAndValue(string $fieldCondition, string $value): void
    {
        $fieldConditionElements = explode('Condition', $fieldCondition);

        if(count($fieldConditionElements) != 2){
            throw new BadRequestException(
                sprintf('%s has invalid format', $fieldCondition)
            );
        }

        list($field,$condition) = $fieldConditionElements;

        if(!in_array($field,$this->allowedFields())){
            throw new BadRequestException(
                sprintf('%s is not a valid query param', $field)
            );
        }

        if(!in_array($condition,self::allowedConditions)){
            throw new BadRequestException(
                sprintf('%s is nor a valid query condition param', $condition)
            );
        }

        if(in_array($field,$this->allowedDateTimeFields())){
            try{
                Assertion::date($value,self::DATE_FORMAT);
            }catch (\Throwable $e){
                throw new BadRequestException(
                    sprintf('%s has invalid datime format. Must be %s', $field,self::DATE_FORMAT)
                );
            }
        }

        if(in_array($field,$this->allowedBooleanFields())){
            try{
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                Assertion::boolean($value);
            }catch (\Throwable $e){
                throw new BadRequestException(
                    sprintf('%s has invalid boolean format', $field)
                );
            }
        }

        if(
            !in_array($field, $this->allowedRangeFields())
        ){
            if( $condition === self::GT || $condition === self::LT )
                throw new BadRequestException(
                    sprintf('%s has invalid Condition %s', $field, $condition)
                );
        }

        $this->addFilter($field,$condition,$value);

    }


    private function addFilter(string $field, string $condition, string|bool $value): void
    {

        if($value !== true && $value !== false){
            $valuesParams = explode(self::MULTIPLE_VALUE_PARAMS_SEPARATOR, $value);
            if(count($valuesParams) > 1){
                $filter = new Filter(
                    $field,
                    $condition,
                    $value,
                    true,
                    $valuesParams
                );
                $this->filters[] = $filter;
                return;
            }
        }

        $filter = new Filter(
            $field,
            $condition,
            $value,
        );

        $this->filters[] = $filter;
    }

}