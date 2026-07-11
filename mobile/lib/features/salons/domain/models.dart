import 'package:flutter/material.dart';

/// Salon summary as returned by GET /salons and nested in details.
class Salon {
  const Salon({
    required this.id,
    required this.slug,
    required this.name,
    this.description,
    this.address,
    this.phone,
    this.instagram,
    this.brandColorHex,
    this.logoUrl,
  });

  final int id;
  final String slug;
  final String name;
  final String? description;
  final String? address;
  final String? phone;
  final String? instagram;
  final String? brandColorHex;
  final String? logoUrl;

  /// The salon's brand color, falling back to the platform default.
  Color get brandColor {
    final hex = brandColorHex;
    if (hex == null || hex.isEmpty) return const Color(0xFFDB2777);
    final cleaned = hex.replaceFirst('#', '');
    if (cleaned.length != 6) return const Color(0xFFDB2777);
    final value = int.tryParse(cleaned, radix: 16);
    if (value == null) return const Color(0xFFDB2777);
    return Color(0xFF000000 | value);
  }

  factory Salon.fromJson(Map<String, dynamic> json) => Salon(
        id: json['id'] as int,
        slug: json['slug'] as String,
        name: json['name'] as String,
        description: json['description'] as String?,
        address: json['address'] as String?,
        phone: json['phone'] as String?,
        instagram: json['instagram'] as String?,
        brandColorHex: json['brand_color'] as String?,
        logoUrl: json['logo_url'] as String?,
      );
}

class SalonService {
  const SalonService({
    required this.id,
    required this.name,
    required this.durationMinutes,
    this.description,
    this.price,
  });

  final int id;
  final String name;
  final String? description;
  final int durationMinutes;
  final double? price;

  factory SalonService.fromJson(Map<String, dynamic> json) => SalonService(
        id: json['id'] as int,
        name: json['name'] as String,
        description: json['description'] as String?,
        durationMinutes: json['duration_minutes'] as int,
        price: (json['price'] as num?)?.toDouble(),
      );
}

class Employee {
  const Employee({
    required this.id,
    required this.name,
    this.specialties,
    this.avatarUrl,
  });

  final int id;
  final String name;
  final String? specialties;
  final String? avatarUrl;

  String get initials {
    final parts = name.trim().split(RegExp(r'\s+'));
    if (parts.isEmpty) return '?';
    if (parts.length == 1) return parts.first.characters.first.toUpperCase();
    return (parts.first.characters.first + parts.last.characters.first)
        .toUpperCase();
  }

  factory Employee.fromJson(Map<String, dynamic> json) => Employee(
        id: json['id'] as int,
        name: json['name'] as String,
        specialties: json['specialties'] as String?,
        avatarUrl: json['avatar_url'] as String?,
      );
}

class WorkingHour {
  const WorkingHour({
    required this.dayOfWeek,
    required this.isClosed,
    this.startTime,
    this.endTime,
  });

  /// 0 = Sunday … 6 = Saturday (matches the backend).
  final int dayOfWeek;
  final bool isClosed;
  final String? startTime;
  final String? endTime;

  factory WorkingHour.fromJson(Map<String, dynamic> json) => WorkingHour(
        dayOfWeek: json['day_of_week'] as int,
        isClosed: json['is_closed'] as bool,
        startTime: json['start_time'] as String?,
        endTime: json['end_time'] as String?,
      );
}

/// Full payload of GET /salons/{slug}.
class SalonDetails {
  const SalonDetails({
    required this.salon,
    required this.services,
    required this.employees,
    required this.workingHours,
    required this.blockedDates,
  });

  final Salon salon;
  final List<SalonService> services;
  final List<Employee> employees;
  final List<WorkingHour> workingHours;
  final Set<String> blockedDates;

  bool isClosedOn(DateTime date) {
    final weekday = date.weekday % 7; // DateTime: Mon=1..Sun=7 → Sun=0
    final hours = workingHours.where((h) => h.dayOfWeek == weekday);
    if (hours.isNotEmpty && hours.first.isClosed) return true;

    final key =
        '${date.year.toString().padLeft(4, '0')}-${date.month.toString().padLeft(2, '0')}-${date.day.toString().padLeft(2, '0')}';
    return blockedDates.contains(key);
  }

  factory SalonDetails.fromJson(Map<String, dynamic> json) => SalonDetails(
        salon: Salon.fromJson(json['salon'] as Map<String, dynamic>),
        services: (json['services'] as List)
            .map((s) => SalonService.fromJson(s as Map<String, dynamic>))
            .toList(),
        employees: (json['employees'] as List)
            .map((e) => Employee.fromJson(e as Map<String, dynamic>))
            .toList(),
        workingHours: (json['working_hours'] as List)
            .map((h) => WorkingHour.fromJson(h as Map<String, dynamic>))
            .toList(),
        blockedDates:
            (json['blocked_dates'] as List).map((d) => d.toString()).toSet(),
      );
}
