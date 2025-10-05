<?php

namespace App\DBAL\Types;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class EnumType extends Type
{
    abstract public static function getTypeName(): string;

    /**
     * @return class-string<\BackedEnum>
     */
    abstract public static function getEnumClass(): string;

    public function getName(): string
    {
        return static::getTypeName();
    }

    public static function getSQLCreateQuery(): string
    {
        $name = static::getTypeName();
        $sql = static::getSQL();
        return "CREATE TYPE \"$name\" AS $sql";
    }

    public static function getSQLDropQuery(): string
    {
        $name = static::getTypeName();
        return "DROP TYPE \"$name\"";
    }

    public static function getSQL(): string
    {
        $cases = static::getEnumClass()::cases();
        $values = array_map(fn ($c) => $c->value, $cases); 

        return "ENUM('" . implode("','", $values) . "')";
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return static::getTypeName();
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): mixed
    {
        return static::getEnumClass()::from($value);
    }

    /**
     * @param \BackedEnumCase  $value
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): string
    {
        return $value->value;
    }
}
