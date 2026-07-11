import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

/// Design system for the app: Material 3 seeded from the platform brand
/// pink, soft rounded surfaces, and language-appropriate type (Inter for
/// Latin, Cairo for Arabic — mirroring the web product).
ThemeData buildTheme({required bool arabic, Color? seed}) {
  final colorScheme = ColorScheme.fromSeed(
    seedColor: seed ?? const Color(0xFFDB2777),
    brightness: Brightness.light,
  );

  final baseText =
      arabic ? GoogleFonts.cairoTextTheme() : GoogleFonts.interTextTheme();

  final textTheme = baseText.apply(
    bodyColor: const Color(0xFF111827),
    displayColor: const Color(0xFF111827),
  );

  return ThemeData(
    useMaterial3: true,
    colorScheme: colorScheme,
    scaffoldBackgroundColor: const Color(0xFFF9FAFB),
    textTheme: textTheme,
    appBarTheme: AppBarTheme(
      backgroundColor: const Color(0xFFF9FAFB),
      foregroundColor: const Color(0xFF111827),
      elevation: 0,
      scrolledUnderElevation: 0.5,
      centerTitle: false,
      titleTextStyle: textTheme.titleLarge?.copyWith(
        fontWeight: FontWeight.w600,
        fontSize: 19,
      ),
    ),
    cardTheme: CardThemeData(
      elevation: 0,
      color: Colors.white,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(20),
        side: const BorderSide(color: Color(0x0D030712)),
      ),
      margin: EdgeInsets.zero,
    ),
    filledButtonTheme: FilledButtonThemeData(
      style: FilledButton.styleFrom(
        minimumSize: const Size.fromHeight(52),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(14),
        ),
        textStyle: textTheme.titleSmall?.copyWith(fontWeight: FontWeight.w600),
      ),
    ),
    outlinedButtonTheme: OutlinedButtonThemeData(
      style: OutlinedButton.styleFrom(
        minimumSize: const Size.fromHeight(52),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(14),
        ),
        side: const BorderSide(color: Color(0xFFD1D5DB)),
        foregroundColor: const Color(0xFF374151),
        textStyle: textTheme.titleSmall?.copyWith(fontWeight: FontWeight.w600),
      ),
    ),
    inputDecorationTheme: InputDecorationTheme(
      filled: true,
      fillColor: Colors.white,
      contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 15),
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(12),
        borderSide: const BorderSide(color: Color(0xFFD1D5DB)),
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(12),
        borderSide: const BorderSide(color: Color(0xFFD1D5DB)),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(12),
        borderSide: BorderSide(color: colorScheme.primary, width: 2),
      ),
    ),
    chipTheme: ChipThemeData(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
      side: const BorderSide(color: Color(0xFFE5E7EB)),
      backgroundColor: Colors.white,
      selectedColor: colorScheme.primary,
      labelStyle: textTheme.bodyMedium,
    ),
    dividerTheme: const DividerThemeData(
      color: Color(0xFFF3F4F6),
      thickness: 1,
    ),
    snackBarTheme: SnackBarThemeData(
      behavior: SnackBarBehavior.floating,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
    ),
  );
}
