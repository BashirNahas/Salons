import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../features/booking/presentation/booking_flow_screen.dart';
import '../features/my_bookings/presentation/my_bookings_screen.dart';
import '../features/salons/presentation/salon_detail_screen.dart';
import '../features/salons/presentation/salons_screen.dart';
import '../features/settings/presentation/settings_screen.dart';
import 'shell.dart';

final router = GoRouter(
  initialLocation: '/salons',
  routes: [
    StatefulShellRoute.indexedStack(
      builder: (context, state, shell) => AppShell(shell: shell),
      branches: [
        StatefulShellBranch(
          routes: [
            GoRoute(
              path: '/salons',
              pageBuilder: (context, state) =>
                  const NoTransitionPage(child: SalonsScreen()),
              routes: [
                GoRoute(
                  path: ':slug',
                  pageBuilder: (context, state) => _fadeThrough(
                    state,
                    SalonDetailScreen(slug: state.pathParameters['slug']!),
                  ),
                  routes: [
                    GoRoute(
                      path: 'book',
                      pageBuilder: (context, state) => _fadeThrough(
                        state,
                        BookingFlowScreen(
                          slug: state.pathParameters['slug']!,
                          preselectedServiceId: int.tryParse(
                            state.uri.queryParameters['service'] ?? '',
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ],
        ),
        StatefulShellBranch(
          routes: [
            GoRoute(
              path: '/bookings',
              pageBuilder: (context, state) =>
                  const NoTransitionPage(child: MyBookingsScreen()),
            ),
          ],
        ),
        StatefulShellBranch(
          routes: [
            GoRoute(
              path: '/settings',
              pageBuilder: (context, state) =>
                  const NoTransitionPage(child: SettingsScreen()),
            ),
          ],
        ),
      ],
    ),
  ],
);

/// Subtle fade + slide-up used for pushed screens.
CustomTransitionPage<void> _fadeThrough(GoRouterState state, Widget child) {
  return CustomTransitionPage<void>(
    key: state.pageKey,
    child: child,
    transitionDuration: const Duration(milliseconds: 260),
    transitionsBuilder: (context, animation, secondary, child) {
      final curved = CurvedAnimation(
        parent: animation,
        curve: Curves.easeOutCubic,
      );
      return FadeTransition(
        opacity: curved,
        child: SlideTransition(
          position: Tween<Offset>(
            begin: const Offset(0, 0.03),
            end: Offset.zero,
          ).animate(curved),
          child: child,
        ),
      );
    },
  );
}
