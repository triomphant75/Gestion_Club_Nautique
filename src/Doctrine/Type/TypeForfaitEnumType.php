<?php

namespace App\Doctrine\Type;

use App\Entity\TypeForfait;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

class TypeForfaitEnumType extends Type
{
   const TYPEFORFAIT = 'typeForfait'; // Type de colonne personnalisé

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        return TypeForfait::from($value); // Retourner l'énumération correspondante
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
{
    // Si la valeur est une instance de TypeForfait, retourner sa valeur sous forme de chaîne
    if ($value instanceof TypeForfait) {
        return $value->value; // Retourner la valeur sous forme de chaîne (par exemple, 'Forfait1')
    }

    // Si ce n'est pas une instance de TypeForfait, échouer
    throw ConversionException::conversionFailed($value, $this->getName());
}


    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "VARCHAR(255)"; // Déclaration du type dans la base
    }


    public function getName()
    {
        return self::TYPEFORFAIT;
    }
}
