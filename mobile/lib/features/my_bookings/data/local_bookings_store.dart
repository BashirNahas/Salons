import 'dart:convert';

import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:shared_preferences/shared_preferences.dart';

import '../../settings/settings_providers.dart';

/// A booking reference kept on-device so customers can revisit their
/// appointments without accounts. The authoritative status always comes
/// from the API (looked up with the stored phone number).
class BookingRef {
  const BookingRef({
    required this.bookingId,
    required this.salonSlug,
    required this.salonName,
    required this.serviceName,
    required this.phone,
    required this.date,
    required this.time,
    required this.createdAtMillis,
  });

  final int bookingId;
  final String salonSlug;
  final String salonName;
  final String serviceName;
  final String phone;
  final String date;
  final String time;
  final int createdAtMillis;

  Map<String, dynamic> toJson() => {
        'bookingId': bookingId,
        'salonSlug': salonSlug,
        'salonName': salonName,
        'serviceName': serviceName,
        'phone': phone,
        'date': date,
        'time': time,
        'createdAtMillis': createdAtMillis,
      };

  factory BookingRef.fromJson(Map<String, dynamic> json) => BookingRef(
        bookingId: json['bookingId'] as int,
        salonSlug: json['salonSlug'] as String,
        salonName: json['salonName'] as String,
        serviceName: json['serviceName'] as String,
        phone: json['phone'] as String,
        date: json['date'] as String,
        time: json['time'] as String,
        createdAtMillis: json['createdAtMillis'] as int,
      );
}

final localBookingsProvider =
    NotifierProvider<LocalBookingsNotifier, List<BookingRef>>(
  LocalBookingsNotifier.new,
);

class LocalBookingsNotifier extends Notifier<List<BookingRef>> {
  static const _key = 'booking_refs_v1';

  SharedPreferences get _prefs => ref.read(sharedPreferencesProvider);

  @override
  List<BookingRef> build() {
    final raw = _prefs.getString(_key);
    if (raw == null) return const [];

    try {
      final list = jsonDecode(raw) as List;
      final refs = list
          .map((r) => BookingRef.fromJson(r as Map<String, dynamic>))
          .toList();
      refs.sort((a, b) => b.createdAtMillis.compareTo(a.createdAtMillis));
      return refs;
    } catch (_) {
      return const [];
    }
  }

  Future<void> add(BookingRef bookingRef) async {
    state = [bookingRef, ...state];
    await _persist();
  }

  Future<void> remove(int bookingId) async {
    state = state.where((r) => r.bookingId != bookingId).toList();
    await _persist();
  }

  Future<void> _persist() async {
    await _prefs.setString(
      _key,
      jsonEncode(state.map((r) => r.toJson()).toList()),
    );
  }
}
