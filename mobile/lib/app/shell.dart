import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../l10n/gen/app_localizations.dart';

/// Bottom-navigation shell. On wide screens (tablets, foldables opened,
/// landscape) the bar becomes a navigation rail so the layout stays
/// natural on every form factor.
class AppShell extends StatelessWidget {
  const AppShell({super.key, required this.shell});

  final StatefulNavigationShell shell;

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    final wide = MediaQuery.sizeOf(context).width >= 800;

    final destinations = [
      (
        icon: Icons.storefront_outlined,
        active: Icons.storefront,
        label: l10n.tabSalons
      ),
      (
        icon: Icons.event_note_outlined,
        active: Icons.event_note,
        label: l10n.tabMyBookings
      ),
      (
        icon: Icons.settings_outlined,
        active: Icons.settings,
        label: l10n.tabSettings
      ),
    ];

    if (wide) {
      return Scaffold(
        body: Row(
          children: [
            NavigationRail(
              selectedIndex: shell.currentIndex,
              onDestinationSelected: _goBranch,
              labelType: NavigationRailLabelType.all,
              groupAlignment: -0.9,
              destinations: [
                for (final d in destinations)
                  NavigationRailDestination(
                    icon: Icon(d.icon),
                    selectedIcon: Icon(d.active),
                    label: Text(d.label),
                  ),
              ],
            ),
            const VerticalDivider(width: 1),
            Expanded(child: shell),
          ],
        ),
      );
    }

    return Scaffold(
      body: shell,
      bottomNavigationBar: NavigationBar(
        selectedIndex: shell.currentIndex,
        onDestinationSelected: _goBranch,
        destinations: [
          for (final d in destinations)
            NavigationDestination(
              icon: Icon(d.icon),
              selectedIcon: Icon(d.active),
              label: d.label,
            ),
        ],
      ),
    );
  }

  void _goBranch(int index) {
    shell.goBranch(index, initialLocation: index == shell.currentIndex);
  }
}
