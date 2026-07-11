import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../../core/api/api_client.dart';
import '../domain/models.dart';

final salonRepositoryProvider = Provider<SalonRepository>(
  (ref) => SalonRepository(ref.watch(apiClientProvider)),
);

/// All salon reads. Pure data access — business rules live on the server.
class SalonRepository {
  SalonRepository(this._api);

  final ApiClient _api;

  Future<List<Salon>> list({String? query}) async {
    final json = await _api.get(
      '/salons',
      query: query == null || query.isEmpty ? null : {'q': query},
    );

    return (json['data'] as List)
        .map((s) => Salon.fromJson(s as Map<String, dynamic>))
        .toList();
  }

  Future<SalonDetails> details(String slug) async {
    final json = await _api.get('/salons/$slug');

    return SalonDetails.fromJson(json['data'] as Map<String, dynamic>);
  }

  Future<List<String>> slots({
    required String slug,
    required int serviceId,
    required DateTime date,
    int? employeeId,
  }) async {
    final json = await _api.get(
      '/salons/$slug/slots',
      query: {
        'service_id': serviceId,
        'date':
            '${date.year.toString().padLeft(4, '0')}-${date.month.toString().padLeft(2, '0')}-${date.day.toString().padLeft(2, '0')}',
        if (employeeId != null) 'employee_id': employeeId,
      },
    );

    return ((json['data'] as Map<String, dynamic>)['slots'] as List)
        .map((s) => s.toString())
        .toList();
  }
}

final salonsProvider =
    FutureProvider.family<List<Salon>, String>((ref, query) async {
  return ref.watch(salonRepositoryProvider).list(query: query);
});

final salonDetailsProvider =
    FutureProvider.family<SalonDetails, String>((ref, slug) async {
  return ref.watch(salonRepositoryProvider).details(slug);
});
