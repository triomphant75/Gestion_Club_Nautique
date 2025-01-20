<?php

namespace App\Entity;

enum TypeNiveauClient: string {
    case DEBUTANT = 'Débutant';
    case INTERMEDIARE = 'Intermédiaire';
    case EXPERT = 'Expert';

    public static function getAvailableTypes(): array {
        return [
            self::DEBUTANT->value,
            self::INTERMEDIARE->value,
            self::EXPERT->value,
        ];
    }
}