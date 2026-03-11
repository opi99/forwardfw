<?php

declare(strict_types=1);

/**
 * This file is part of ForwardFW a web application framework.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace ForwardFW\DataHandling;

use ForwardFW\Service\AbstractService;

/**
 * Helper functions to work with slugs on entities
 */
class SlugHelper
{
    public static function slugify(string $string, array $config = []): string
    {
        $replace = $config['replace'] ?? '-';
        $lower = $config['lower'] ?? true;
        $maxLength = $config['maxLength'] ?? 100;

        // Entferne unsichtbare/control chars
        $string = preg_replace('/[\p{C}]/u', '', $string);

        // Punkte zwischen Buchstaben löschen (z.B. e.V. -> eV)
        $string = preg_replace('/(?<=\p{L})\.(?=\p{L})/u', '', $string);

        // Unicode-normalisieren (optional, entfernt diakritische Zeichen)
        if (class_exists('\Normalizer')) {
            $string = \Normalizer::normalize($string, \Normalizer::FORM_KC);
        }

        // Sonderzeichen, Whitespaces und Nicht-Buchstaben in $replace umwandeln
        $string = preg_replace('/[^\p{L}\p{Nd}]+/u', $replace, $string);

        // Mehrfachersetzungen auf eins reduzieren
        $string = preg_replace('/' . preg_quote($replace, '/') . '+/', $replace, $string);

        // $replace am Anfang/Ende entfernen
        $string = trim($string, $replace);

        if ($lower) {
            $string = mb_strtolower($string, 'UTF-8');
        }

        // maxLength beachten
        if (mb_strlen($string) > $maxLength) {
            $string = mb_substr($string, 0, $maxLength);
            // ggf. Endersatzzeichen entfernen
            $string = rtrim($string, $replace);
        }

        return $string;
    }
}
