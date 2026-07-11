import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../../core/api/api_client.dart';
import '../domain/booking.dart';

final bookingRepositoryProvider = Provider<BookingRepository>(
  (ref) => BookingRepository(ref.watch(apiClientProvider)),
);

class BookingRepository {
  BookingRepository(this._api);

  final ApiClient _api;

  /// Creates a pending booking request. Throws [ApiException] with the
  /// server's localized message when the slot was just taken (422) or
  /// validation fails.
  Future<Booking> create({
    required String salonSlug,
    required int serviceId,
    int? employeeId,
    required String date,
    required String time,
    required String customerName,
    required String customerPhone,
    String? customerEmail,
    String? notes,
  }) async {
    final json = await _api.post(
      '/salons/$salonSlug/bookings',
      body: {
        'service_id': serviceId,
        if (employeeId != null) 'employee_id': employeeId,
        'date': date,
        'time': time,
        'customer_name': customerName,
        'customer_phone': customerPhone,
        if (customerEmail != null && customerEmail.isNotEmpty)
          'customer_email': customerEmail,
        if (notes != null && notes.isNotEmpty) 'notes': notes,
      },
    );

    return Booking.fromJson(json['data'] as Map<String, dynamic>);
  }

  /// Fetches the current status of a booking. The phone number must match
  /// the one the booking was made with (server enforces this).
  Future<Booking> status({
    required String salonSlug,
    required int bookingId,
    required String phone,
  }) async {
    final json = await _api.get(
      '/salons/$salonSlug/bookings/$bookingId',
      query: {'phone': phone},
    );

    return Booking.fromJson(json['data'] as Map<String, dynamic>);
  }
}
