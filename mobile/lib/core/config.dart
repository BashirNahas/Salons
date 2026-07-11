/// Compile-time configuration.
///
/// Override the API host when building:
///   flutter build apk --dart-define=API_BASE_URL=https://your-host/api/v1
library;

const String kApiBaseUrl = String.fromEnvironment(
  'API_BASE_URL',
  defaultValue: 'https://salons.synaptix.sy/api/v1',
);
