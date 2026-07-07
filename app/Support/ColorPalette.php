<?php

namespace App\Support;

/**
 * Generates a Tailwind-style 50..950 shade ramp from a single base hex
 * color, so a salon owner can pick one brand color and have it applied
 * consistently across buttons, accents, and gradients on their public
 * booking page (which otherwise hardcodes the pink "brand" palette).
 */
class ColorPalette
{
    private const DEFAULT_HEX = '#db2777';

    /** Lightness target (0-100) for each Tailwind-style shade step. */
    private const LIGHTNESS_STEPS = [
        50 => 97, 100 => 93, 200 => 85, 300 => 75, 400 => 65,
        500 => 55, 600 => 45, 700 => 37, 800 => 30, 900 => 24, 950 => 15,
    ];

    /**
     * @return array<int, string> shade => "#rrggbb"
     */
    public static function fromHex(?string $hex): array
    {
        $hex = self::isValidHex($hex) ? $hex : self::DEFAULT_HEX;

        [$h, $s] = self::hexToHsl($hex);

        $palette = [];
        foreach (self::LIGHTNESS_STEPS as $shade => $lightness) {
            $palette[$shade] = self::hslToHex($h, $s, $lightness);
        }
        // Preserve the exact color the owner picked at the 600 step (the
        // primary "brand-600" shade used for buttons/links) rather than the
        // lossy round-trip through HSL lightness reassignment.
        $palette[600] = $hex;

        return $palette;
    }

    public static function isValidHex(?string $hex): bool
    {
        return is_string($hex) && preg_match('/^#[0-9a-fA-F]{6}$/', $hex) === 1;
    }

    /**
     * @return array{0: float, 1: float} [hue 0-360, saturation 0-100]
     */
    private static function hexToHsl(string $hex): array
    {
        [$r, $g, $b] = sscanf($hex, '#%02x%02x%02x');
        $r /= 255;
        $g /= 255;
        $b /= 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $delta = $max - $min;

        $l = ($max + $min) / 2;

        if ($delta === 0.0) {
            return [0.0, 0.0];
        }

        $s = $delta / (1 - abs(2 * $l - 1));

        $h = match ($max) {
            $r => fmod((($g - $b) / $delta), 6),
            $g => (($b - $r) / $delta) + 2,
            default => (($r - $g) / $delta) + 4,
        };
        $h *= 60;
        if ($h < 0) {
            $h += 360;
        }

        return [$h, $s * 100];
    }

    private static function hslToHex(float $h, float $s, float $l): string
    {
        $s /= 100;
        $l /= 100;

        $c = (1 - abs(2 * $l - 1)) * $s;
        $x = $c * (1 - abs(fmod($h / 60, 2) - 1));
        $m = $l - $c / 2;

        [$r, $g, $b] = match (true) {
            $h < 60 => [$c, $x, 0],
            $h < 120 => [$x, $c, 0],
            $h < 180 => [0, $c, $x],
            $h < 240 => [0, $x, $c],
            $h < 300 => [$x, 0, $c],
            default => [$c, 0, $x],
        };

        $r = (int) round(($r + $m) * 255);
        $g = (int) round(($g + $m) * 255);
        $b = (int) round(($b + $m) * 255);

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }
}
