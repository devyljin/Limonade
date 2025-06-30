<?php

namespace Agrume\Limonade\DTO;

use Agrume\Limonade\DTO\Attributes\Property;
use ReflectionClass;
use ReflectionProperty;

/**
 * Utility for validating DTOs based on property attributes.
 */
class Validator
{
    /**
     * Validate a DTO against its property attributes.
     *
     * @param object $dto The DTO to validate
     * @return array Array of validation errors, empty if valid
     */
    public static function validate(object $dto): array
    {
        $errors = [];
        $reflection = new ReflectionClass($dto);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

        foreach ($properties as $property) {
            $attributes = $property->getAttributes(Property::class);
            
            if (empty($attributes)) {
                continue;
            }
            
            /** @var Property $propertyAttribute */
            $propertyAttribute = $attributes[0]->newInstance();
            $propertyValue = $property->getValue($dto);
            $propertyName = $property->getName();
            
            // Check required fields
            if ($propertyAttribute->required && ($propertyValue === null || $propertyValue === '')) {
                $errors[] = "Property '{$propertyName}' is required but is empty or null.";
                continue;
            }
            
            // Skip further validation if the value is null and not required
            if ($propertyValue === null && !$propertyAttribute->required) {
                continue;
            }
            
            // Apply additional validation rules if any
            if (!empty($propertyAttribute->validationRules)) {
                foreach ($propertyAttribute->validationRules as $rule => $params) {
                    $error = self::validateRule($propertyName, $propertyValue, $rule, $params);
                    if ($error) {
                        $errors[] = $error;
                    }
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Validate a property value against a specific rule.
     *
     * @param string $propertyName Name of the property
     * @param mixed $value Property value
     * @param string $rule Validation rule name
     * @param mixed $params Rule parameters
     * @return string|null Error message if validation fails, null otherwise
     */
    private static function validateRule(string $propertyName, mixed $value, string $rule, mixed $params): ?string
    {
        return match ($rule) {
            'minLength' => strlen((string) $value) < $params 
                ? "Property '{$propertyName}' must be at least {$params} characters long."
                : null,
            'maxLength' => strlen((string) $value) > $params
                ? "Property '{$propertyName}' cannot exceed {$params} characters."
                : null,
            'min' => $value < $params
                ? "Property '{$propertyName}' must be at least {$params}."
                : null,
            'max' => $value > $params
                ? "Property '{$propertyName}' cannot exceed {$params}."
                : null,
            'regex' => !preg_match($params, (string) $value)
                ? "Property '{$propertyName}' does not match the required format."
                : null,
            'enum' => !in_array($value, $params)
                ? "Property '{$propertyName}' must be one of: " . implode(', ', $params) . "."
                : null,
            'email' => !filter_var($value, FILTER_VALIDATE_EMAIL)
                ? "Property '{$propertyName}' must be a valid email address."
                : null,
            default => "Unknown validation rule: {$rule}"
        };
    }
} 