import 'package:flutter_test/flutter_test.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:salons_app/app/app.dart';
import 'package:salons_app/features/settings/settings_providers.dart';
import 'package:shared_preferences/shared_preferences.dart';

void main() {
  setUpAll(() {
    // No network in tests: fall back to bundled fonts.
    GoogleFonts.config.allowRuntimeFetching = false;
  });

  testWidgets('app boots, localizes, and shows the main navigation',
      (tester) async {
    SharedPreferences.setMockInitialValues({'locale': 'en'});
    final prefs = await SharedPreferences.getInstance();

    await tester.pumpWidget(
      ProviderScope(
        overrides: [sharedPreferencesProvider.overrideWithValue(prefs)],
        child: const SalonsApp(),
      ),
    );
    await tester.pump();

    // Discovery screen headline + the three navigation tabs.
    expect(find.text('Find your salon'), findsOneWidget);
    expect(find.text('Salons'), findsWidgets);
    expect(find.text('My Bookings'), findsOneWidget);
    expect(find.text('Settings'), findsOneWidget);

    // Let the HTTP client's timeout timers expire before the test ends.
    await tester.pump(const Duration(seconds: 40));
  });

  testWidgets('app boots in Arabic with RTL', (tester) async {
    SharedPreferences.setMockInitialValues({'locale': 'ar'});
    final prefs = await SharedPreferences.getInstance();

    await tester.pumpWidget(
      ProviderScope(
        overrides: [sharedPreferencesProvider.overrideWithValue(prefs)],
        child: const SalonsApp(),
      ),
    );
    await tester.pump();

    expect(find.text('اعثر على صالونك'), findsOneWidget);
    expect(find.text('حجوزاتي'), findsOneWidget);

    await tester.pump(const Duration(seconds: 40));
  });
}
