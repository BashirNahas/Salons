import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../../l10n/gen/app_localizations.dart';
import '../settings_providers.dart';

class _LanguageTile extends StatelessWidget {
  const _LanguageTile({
    required this.label,
    required this.selected,
    required this.onTap,
  });

  final String label;
  final bool selected;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return ListTile(
      onTap: onTap,
      title: Text(label),
      trailing: selected
          ? Icon(Icons.check_circle, color: theme.colorScheme.primary)
          : const Icon(Icons.circle_outlined, color: Color(0xFFD1D5DB)),
    );
  }
}

class SettingsScreen extends ConsumerWidget {
  const SettingsScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final l10n = AppLocalizations.of(context);
    final theme = Theme.of(context);
    final locale = ref.watch(localeProvider);

    return Scaffold(
      appBar: AppBar(title: Text(l10n.settingsTitle)),
      body: Center(
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxWidth: 640),
          child: ListView(
            padding: const EdgeInsets.all(20),
            children: [
              Text(
                l10n.languageSection,
                style: theme.textTheme.titleSmall
                    ?.copyWith(fontWeight: FontWeight.w700),
              ),
              const SizedBox(height: 10),
              Card(
                child: Column(
                  children: [
                    _LanguageTile(
                      label: l10n.english,
                      selected: locale.languageCode == 'en',
                      onTap: () =>
                          ref.read(localeProvider.notifier).setLocale('en'),
                    ),
                    const Divider(height: 1),
                    _LanguageTile(
                      label: l10n.arabic,
                      selected: locale.languageCode == 'ar',
                      onTap: () =>
                          ref.read(localeProvider.notifier).setLocale('ar'),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 24),
              Text(
                l10n.aboutApp,
                style: theme.textTheme.titleSmall
                    ?.copyWith(fontWeight: FontWeight.w700),
              ),
              const SizedBox(height: 10),
              Card(
                child: ListTile(
                  leading: const Icon(Icons.content_cut),
                  title: Text(l10n.appTitle),
                  subtitle: Text(l10n.aboutAppBody),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
