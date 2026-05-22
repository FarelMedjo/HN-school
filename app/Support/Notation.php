<?php

namespace App\Support;

class Notation
{
    public const SECTIONS = [
        'francophone' => 'Francophone',
        'anglophone' => 'Anglophone',
        'bilingue' => 'Bilingue',
    ];

    /**
     * Convertit une note /20 en mention/grade selon la section.
     * Bilingue suit le système francophone par défaut.
     */
    public static function mention(?float $note, string $section = 'francophone'): array
    {
        if ($note === null) {
            return ['code' => '—', 'libelle' => '—', 'color' => 'gray'];
        }

        $section = strtolower($section);
        if ($section === 'anglophone') {
            return self::anglophone($note);
        }
        return self::francophone($note);
    }

    public static function francophone(float $note): array
    {
        return match (true) {
            $note >= 16 => ['code' => 'EX', 'libelle' => 'Excellent', 'color' => 'emerald'],
            $note >= 14 => ['code' => 'TB', 'libelle' => 'Très Bien', 'color' => 'green'],
            $note >= 12 => ['code' => 'B',  'libelle' => 'Bien', 'color' => 'sky'],
            $note >= 10 => ['code' => 'AB', 'libelle' => 'Assez Bien', 'color' => 'indigo'],
            $note >= 8  => ['code' => 'P',  'libelle' => 'Passable', 'color' => 'amber'],
            default     => ['code' => 'F',  'libelle' => 'Faible', 'color' => 'rose'],
        };
    }

    public static function anglophone(float $note): array
    {
        return match (true) {
            $note >= 18 => ['code' => 'A+', 'libelle' => 'Outstanding', 'color' => 'emerald'],
            $note >= 16 => ['code' => 'A',  'libelle' => 'Excellent', 'color' => 'green'],
            $note >= 14 => ['code' => 'B+', 'libelle' => 'Very Good', 'color' => 'sky'],
            $note >= 12 => ['code' => 'B',  'libelle' => 'Good', 'color' => 'indigo'],
            $note >= 10 => ['code' => 'C',  'libelle' => 'Average', 'color' => 'amber'],
            $note >= 8  => ['code' => 'D',  'libelle' => 'Below average', 'color' => 'orange'],
            default     => ['code' => 'F',  'libelle' => 'Fail', 'color' => 'rose'],
        };
    }

    /** Retourne juste le libellé (utile en blade). */
    public static function label(?float $note, string $section = 'francophone'): string
    {
        return self::mention($note, $section)['libelle'];
    }
}
