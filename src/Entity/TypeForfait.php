<?php

namespace App\Entity;

enum TypeForfait: string {
    case FORFAIT1 = 'Forfait1';
    case FORFAIT2 = 'Forfait2';
    case FORFAIT3 = 'Forfait3';

    public static function getAvailableTypes(): array {
        return [
            self::FORFAIT1->value,
            self::FORFAIT2->value,
            self::FORFAIT3->value,
        ];
    }
}