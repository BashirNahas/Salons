import 'package:dio/dio.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../config.dart';
import '../../features/settings/settings_providers.dart';

/// Thrown for any failed API call, carrying the server's human-readable
/// message (already localized by the backend) when one exists.
class ApiException implements Exception {
  ApiException(this.message, {this.statusCode, this.fieldErrors = const {}});

  final String message;
  final int? statusCode;
  final Map<String, List<String>> fieldErrors;

  @override
  String toString() => message;
}

final apiClientProvider = Provider<ApiClient>((ref) {
  final locale = ref.watch(localeProvider);

  final dio = Dio(
    BaseOptions(
      baseUrl: kApiBaseUrl,
      connectTimeout: const Duration(seconds: 15),
      receiveTimeout: const Duration(seconds: 20),
      headers: {
        'Accept': 'application/json',
        'Accept-Language': locale.languageCode,
      },
    ),
  );

  return ApiClient(dio);
});

class ApiClient {
  ApiClient(this._dio);

  final Dio _dio;

  Future<Map<String, dynamic>> get(
    String path, {
    Map<String, dynamic>? query,
  }) =>
      _request(
        () => _dio.get<Map<String, dynamic>>(path, queryParameters: query),
      );

  Future<Map<String, dynamic>> post(
    String path, {
    Object? body,
  }) =>
      _request(() => _dio.post<Map<String, dynamic>>(path, data: body));

  Future<Map<String, dynamic>> _request(
    Future<Response<Map<String, dynamic>>> Function() send,
  ) async {
    try {
      final response = await send();
      return response.data ?? <String, dynamic>{};
    } on DioException catch (e) {
      throw _toApiException(e);
    }
  }

  ApiException _toApiException(DioException e) {
    final data = e.response?.data;

    if (data is Map<String, dynamic>) {
      final fieldErrors = <String, List<String>>{};
      final errors = data['errors'];
      if (errors is Map<String, dynamic>) {
        errors.forEach((key, value) {
          if (value is List) {
            fieldErrors[key] = value.map((v) => v.toString()).toList();
          }
        });
      }

      return ApiException(
        (data['message'] as String?) ?? 'Request failed',
        statusCode: e.response?.statusCode,
        fieldErrors: fieldErrors,
      );
    }

    return ApiException(
      e.type == DioExceptionType.connectionError ||
              e.type == DioExceptionType.connectionTimeout ||
              e.type == DioExceptionType.receiveTimeout
          ? 'network'
          : 'Request failed',
      statusCode: e.response?.statusCode,
    );
  }
}
