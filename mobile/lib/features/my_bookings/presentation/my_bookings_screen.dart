import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../../core/widgets/widgets.dart';
import '../../../l10n/gen/app_localizations.dart';
import '../../booking/data/booking_repository.dart';
import '../../booking/domain/booking.dart';
import '../data/local_bookings_store.dart';

/// Live status for one locally saved booking reference.
final bookingStatusProvider =
    FutureProvider.family<Booking, BookingRef>((ref, bookingRef) {
  return ref.read(bookingRepositoryProvider).status(
        salonSlug: bookingRef.salonSlug,
        bookingId: bookingRef.bookingId,
        phone: bookingRef.phone,
      );
});

class MyBookingsScreen extends ConsumerWidget {
  const MyBookingsScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final l10n = AppLocalizations.of(context);
    final refs = ref.watch(localBookingsProvider);

    return Scaffold(
      appBar: AppBar(title: Text(l10n.myBookingsTitle)),
      body: refs.isEmpty
          ? EmptyState(
              icon: Icons.event_note_outlined,
              title: l10n.noBookingsYet,
              body: l10n.noBookingsYetBody,
              action: FilledButton(
                style: FilledButton.styleFrom(
                  minimumSize: const Size(180, 48),
                ),
                onPressed: () => context.go('/salons'),
                child: Text(l10n.exploreSalons),
              ),
            )
          : RefreshIndicator(
              onRefresh: () async {
                for (final r in refs) {
                  ref.invalidate(bookingStatusProvider(r));
                }
              },
              child: Center(
                child: ConstrainedBox(
                  constraints: const BoxConstraints(maxWidth: 640),
                  child: ListView.separated(
                    physics: const AlwaysScrollableScrollPhysics(),
                    padding: const EdgeInsets.all(20),
                    itemCount: refs.length,
                    separatorBuilder: (context, i) =>
                        const SizedBox(height: 12),
                    itemBuilder: (context, i) => StaggeredItem(
                      index: i,
                      child: _BookingCard(bookingRef: refs[i]),
                    ),
                  ),
                ),
              ),
            ),
    );
  }
}

class _BookingCard extends ConsumerWidget {
  const _BookingCard({required this.bookingRef});

  final BookingRef bookingRef;

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final l10n = AppLocalizations.of(context);
    final theme = Theme.of(context);
    final status = ref.watch(bookingStatusProvider(bookingRef));

    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: [
                Expanded(
                  child: Text(
                    bookingRef.salonName,
                    style: theme.textTheme.titleSmall
                        ?.copyWith(fontWeight: FontWeight.w700),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                  ),
                ),
                status.when(
                  data: (booking) => _StatusChip(status: booking.status),
                  loading: () => const SizedBox(
                    width: 14,
                    height: 14,
                    child: CircularProgressIndicator(strokeWidth: 2),
                  ),
                  // Unreachable/deleted booking: keep the card, show nothing.
                  error: (e, s) => const SizedBox.shrink(),
                ),
              ],
            ),
            const SizedBox(height: 6),
            Text(
              bookingRef.serviceName,
              style: theme.textTheme.bodyMedium,
            ),
            const SizedBox(height: 4),
            Directionality(
              textDirection: TextDirection.ltr,
              child: Text(
                '${bookingRef.date} · ${bookingRef.time}',
                style: theme.textTheme.bodySmall?.copyWith(
                  color: const Color(0xFF6B7280),
                  fontFeatures: const [FontFeature.tabularFigures()],
                ),
              ),
            ),
            const SizedBox(height: 8),
            Align(
              alignment: AlignmentDirectional.centerEnd,
              child: TextButton.icon(
                style: TextButton.styleFrom(
                  foregroundColor: const Color(0xFF9CA3AF),
                ),
                onPressed: () async {
                  await ref
                      .read(localBookingsProvider.notifier)
                      .remove(bookingRef.bookingId);
                  if (context.mounted) {
                    ScaffoldMessenger.of(context).showSnackBar(
                      SnackBar(content: Text(l10n.bookingRemoved)),
                    );
                  }
                },
                icon: const Icon(Icons.delete_outline, size: 18),
                label: Text(l10n.removeFromList),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _StatusChip extends StatelessWidget {
  const _StatusChip({required this.status});

  final String status;

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);

    final (label, background, foreground) = switch (status) {
      'approved' => (
          l10n.statusApproved,
          const Color(0xFFD1FAE5),
          const Color(0xFF065F46),
        ),
      'rejected' => (
          l10n.statusRejected,
          const Color(0xFFFEE2E2),
          const Color(0xFF991B1B),
        ),
      'cancelled' => (
          l10n.statusCancelled,
          const Color(0xFFF3F4F6),
          const Color(0xFF4B5563),
        ),
      _ => (
          l10n.statusPending,
          const Color(0xFFFEF3C7),
          const Color(0xFF92400E),
        ),
    };

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(
        color: background,
        borderRadius: BorderRadius.circular(999),
      ),
      child: Text(
        label,
        style: TextStyle(
          color: foreground,
          fontSize: 12,
          fontWeight: FontWeight.w600,
        ),
      ),
    );
  }
}
