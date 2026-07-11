import 'dart:ui';

import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:shared_preferences/shared_preferences.dart';

/// Injected in main() with the real instance.
final sharedPreferencesProvider = Provider<SharedPreferences>(
  (ref) => throw UnimplementedError('Overridden in main()'),
);

final localeProvider = NotifierProvider<LocaleNotifier, Locale>(
  LocaleNotifier.new,
);

class LocaleNotifier extends Notifier<Locale> {
  static const _key = 'locale';

  @override
  Locale build() {
    final saved = ref.read(sharedPreferencesProvider).getString(_key);
    if (saved == 'ar' || saved == 'en') return Locale(saved!);

    // First launch: follow the device language when it's Arabic.
    final device = PlatformDispatcher.instance.locale.languageCode;
    return Locale(device == 'ar' ? 'ar' : 'en');
  }

  Future<void> setLocale(String languageCode) async {
    state = Locale(languageCode);
    await ref.read(sharedPreferencesProvider).setString(_key, languageCode);
  }
}
