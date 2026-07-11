import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../../app/theme.dart';
import '../../../core/widgets/async_view.dart';
import '../../../core/widgets/widgets.dart';
import '../../../l10n/gen/app_localizations.dart';
import '../../settings/settings_providers.dart';
import '../data/salon_repository.dart';
import '../domain/models.dart';

class SalonDetailScreen extends ConsumerWidget {
  const SalonDetailScreen({super.key, required this.slug});

  final String slug;

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final details = ref.watch(salonDetailsProvider(slug));
    final locale = ref.watch(localeProvider);

    return Scaffold(
      body: AsyncView<SalonDetails>(
        value: details,
        onRetry: () => ref.invalidate(salonDetailsProvider(slug)),
        builder: (data) {
          // Re-seed the theme from this salon's brand color so the whole
          // subtree (chips, buttons, prices) wears the salon's identity.
          final branded = buildTheme(
            arabic: locale.languageCode == 'ar',
            seed: data.salon.brandColor,
          );

          return Theme(
            data: branded,
            child: _SalonDetailBody(slug: slug, data: data),
          );
        },
      ),
    );
  }
}

class _SalonDetailBody extends StatelessWidget {
  const _SalonDetailBody({required this.slug, required this.data});

  final String slug;
  final SalonDetails data;

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    final theme = Theme.of(context);
    final salon = data.salon;
    final width = MediaQuery.sizeOf(context).width;
    final maxContentWidth = width >= 900 ? 760.0 : double.infinity;

    return CustomScrollView(
      slivers: [
        SliverAppBar(
          expandedHeight: 230,
          pinned: true,
          backgroundColor: salon.brandColor,
          foregroundColor: Colors.white,
          leading: BackButton(
            onPressed: () => context.go('/salons'),
            color: Colors.white,
          ),
          flexibleSpace: FlexibleSpaceBar(
            background: Container(
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                  colors: [
                    salon.brandColor,
                    Color.lerp(salon.brandColor, Colors.black, 0.45)!,
                  ],
                ),
              ),
              child: SafeArea(
                child: Padding(
                  padding: const EdgeInsets.fromLTRB(20, 48, 20, 20),
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.end,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Hero(
                        tag: 'salon-logo-${salon.slug}',
                        child: Monogram(
                          text: salon.name.characters.first.toUpperCase(),
                          color: Colors.white24,
                          size: 52,
                          imageUrl: salon.logoUrl,
                        ),
                      ),
                      const SizedBox(height: 10),
                      Text(
                        salon.name,
                        style: theme.textTheme.headlineSmall?.copyWith(
                          color: Colors.white,
                          fontWeight: FontWeight.w700,
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                      if (salon.address != null &&
                          salon.address!.isNotEmpty) ...[
                        const SizedBox(height: 4),
                        Row(
                          children: [
                            const Icon(
                              Icons.location_on_outlined,
                              size: 15,
                              color: Colors.white70,
                            ),
                            const SizedBox(width: 4),
                            Flexible(
                              child: Text(
                                salon.address!,
                                style: theme.textTheme.bodySmall
                                    ?.copyWith(color: Colors.white70),
                                maxLines: 1,
                                overflow: TextOverflow.ellipsis,
                              ),
                            ),
                          ],
                        ),
                      ],
                    ],
                  ),
                ),
              ),
            ),
          ),
        ),
        SliverToBoxAdapter(
          child: Center(
            child: ConstrainedBox(
              constraints: BoxConstraints(maxWidth: maxContentWidth),
              child: Padding(
                padding: const EdgeInsets.fromLTRB(20, 20, 20, 110),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    if (salon.description != null &&
                        salon.description!.isNotEmpty) ...[
                      _SectionTitle(l10n.aboutSection),
                      Text(
                        salon.description!,
                        style: theme.textTheme.bodyMedium?.copyWith(
                          color: const Color(0xFF4B5563),
                          height: 1.6,
                        ),
                      ),
                      const SizedBox(height: 24),
                    ],
                    _SectionTitle(l10n.servicesSection),
                    for (final (i, service) in data.services.indexed) ...[
                      StaggeredItem(
                        index: i,
                        child: _ServiceTile(slug: slug, service: service),
                      ),
                      const SizedBox(height: 10),
                    ],
                    if (data.employees.isNotEmpty) ...[
                      const SizedBox(height: 14),
                      _SectionTitle(l10n.teamSection),
                      SizedBox(
                        height: 118,
                        child: ListView.separated(
                          scrollDirection: Axis.horizontal,
                          itemCount: data.employees.length,
                          separatorBuilder: (context, i) =>
                              const SizedBox(width: 12),
                          itemBuilder: (context, i) {
                            final employee = data.employees[i];
                            return SizedBox(
                              width: 96,
                              child: Column(
                                children: [
                                  Monogram(
                                    text: employee.initials,
                                    color: salon.brandColor,
                                    size: 60,
                                    imageUrl: employee.avatarUrl,
                                  ),
                                  const SizedBox(height: 8),
                                  Text(
                                    employee.name,
                                    style: theme.textTheme.bodySmall?.copyWith(
                                      fontWeight: FontWeight.w600,
                                    ),
                                    maxLines: 2,
                                    overflow: TextOverflow.ellipsis,
                                    textAlign: TextAlign.center,
                                  ),
                                ],
                              ),
                            );
                          },
                        ),
                      ),
                    ],
                    const SizedBox(height: 24),
                    _SectionTitle(l10n.hoursSection),
                    Card(
                      child: Padding(
                        padding: const EdgeInsets.symmetric(
                          horizontal: 16,
                          vertical: 6,
                        ),
                        child: Column(
                          children: [
                            for (final hour in data.workingHours)
                              _HourRow(hour: hour),
                          ],
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),
        ),
      ],
    ).withBookBar(context, slug, l10n);
  }
}

extension on Widget {
  /// Pins the primary call-to-action to the bottom of the screen.
  Widget withBookBar(BuildContext context, String slug, AppLocalizations l10n) {
    return Stack(
      children: [
        this,
        Align(
          alignment: Alignment.bottomCenter,
          child: Container(
            padding: EdgeInsets.fromLTRB(
              20,
              12,
              20,
              12 + MediaQuery.paddingOf(context).bottom,
            ),
            decoration: BoxDecoration(
              color: Colors.white,
              border: const Border(top: BorderSide(color: Color(0xFFF3F4F6))),
              boxShadow: [
                BoxShadow(
                  color: Colors.black.withValues(alpha: 0.04),
                  blurRadius: 12,
                  offset: const Offset(0, -4),
                ),
              ],
            ),
            child: SafeArea(
              top: false,
              child: ConstrainedBox(
                constraints: const BoxConstraints(maxWidth: 720),
                child: FilledButton.icon(
                  onPressed: () => context.go('/salons/$slug/book'),
                  icon: const Icon(Icons.calendar_month_outlined),
                  label: Text(l10n.bookAppointment),
                ),
              ),
            ),
          ),
        ),
      ],
    );
  }
}

class _SectionTitle extends StatelessWidget {
  const _SectionTitle(this.text);

  final String text;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 12),
      child: Text(
        text,
        style: Theme.of(context)
            .textTheme
            .titleMedium
            ?.copyWith(fontWeight: FontWeight.w700),
      ),
    );
  }
}

class _ServiceTile extends StatelessWidget {
  const _ServiceTile({required this.slug, required this.service});

  final String slug;
  final SalonService service;

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    final theme = Theme.of(context);

    return Card(
      child: InkWell(
        borderRadius: BorderRadius.circular(20),
        onTap: () => context.go('/salons/$slug/book?service=${service.id}'),
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
          child: Row(
            children: [
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      service.name,
                      style: theme.textTheme.titleSmall
                          ?.copyWith(fontWeight: FontWeight.w600),
                    ),
                    const SizedBox(height: 3),
                    Text(
                      l10n.minutesShort(service.durationMinutes),
                      style: theme.textTheme.bodySmall
                          ?.copyWith(color: const Color(0xFF6B7280)),
                    ),
                  ],
                ),
              ),
              if (service.price != null)
                Padding(
                  padding: const EdgeInsetsDirectional.only(end: 12),
                  child: Text(
                    service.price!.toStringAsFixed(2),
                    style: theme.textTheme.titleSmall?.copyWith(
                      fontWeight: FontWeight.w700,
                      color: theme.colorScheme.primary,
                    ),
                  ),
                ),
              FilledButton.tonal(
                style: FilledButton.styleFrom(
                  minimumSize: const Size(0, 40),
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                ),
                onPressed: () =>
                    context.go('/salons/$slug/book?service=${service.id}'),
                child: Text(l10n.bookThisService),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _HourRow extends StatelessWidget {
  const _HourRow({required this.hour});

  final WorkingHour hour;

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    final theme = Theme.of(context);
    final locale = Localizations.localeOf(context).languageCode;

    // Backend: 0 = Sunday … 6 = Saturday.
    const enDays = [
      'Sunday',
      'Monday',
      'Tuesday',
      'Wednesday',
      'Thursday',
      'Friday',
      'Saturday',
    ];
    const arDays = [
      'الأحد',
      'الاثنين',
      'الثلاثاء',
      'الأربعاء',
      'الخميس',
      'الجمعة',
      'السبت',
    ];
    final day = (locale == 'ar' ? arDays : enDays)[hour.dayOfWeek.clamp(0, 6)];

    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 9),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            day,
            style: theme.textTheme.bodyMedium
                ?.copyWith(fontWeight: FontWeight.w500),
          ),
          hour.isClosed
              ? Text(
                  l10n.closedLabel,
                  style: theme.textTheme.bodyMedium
                      ?.copyWith(color: const Color(0xFF9CA3AF)),
                )
              : Directionality(
                  // Times always read left-to-right, even in Arabic.
                  textDirection: TextDirection.ltr,
                  child: Text(
                    '${hour.startTime} – ${hour.endTime}',
                    style: theme.textTheme.bodyMedium?.copyWith(
                      color: const Color(0xFF4B5563),
                      fontFeatures: const [FontFeature.tabularFigures()],
                    ),
                  ),
                ),
        ],
      ),
    );
  }
}
