import 'dart:async';

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../../core/widgets/async_view.dart';
import '../../../core/widgets/widgets.dart';
import '../../../l10n/gen/app_localizations.dart';
import '../data/salon_repository.dart';
import '../domain/models.dart';

class SalonsScreen extends ConsumerStatefulWidget {
  const SalonsScreen({super.key});

  @override
  ConsumerState<SalonsScreen> createState() => _SalonsScreenState();
}

class _SalonsScreenState extends ConsumerState<SalonsScreen> {
  String _query = '';
  Timer? _debounce;

  @override
  void dispose() {
    _debounce?.cancel();
    super.dispose();
  }

  void _onSearch(String value) {
    _debounce?.cancel();
    _debounce = Timer(const Duration(milliseconds: 350), () {
      if (mounted) setState(() => _query = value.trim());
    });
  }

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    final theme = Theme.of(context);
    final salons = ref.watch(salonsProvider(_query));

    return Scaffold(
      body: SafeArea(
        child: RefreshIndicator(
          onRefresh: () => ref.refresh(salonsProvider(_query).future),
          child: CustomScrollView(
            physics: const AlwaysScrollableScrollPhysics(),
            slivers: [
              SliverPadding(
                padding: const EdgeInsets.fromLTRB(20, 24, 20, 4),
                sliver: SliverToBoxAdapter(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        l10n.salonsTitle,
                        style: theme.textTheme.headlineSmall
                            ?.copyWith(fontWeight: FontWeight.w700),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        l10n.salonsSubtitle,
                        style: theme.textTheme.bodyMedium
                            ?.copyWith(color: const Color(0xFF6B7280)),
                      ),
                      const SizedBox(height: 16),
                      TextField(
                        onChanged: _onSearch,
                        textInputAction: TextInputAction.search,
                        decoration: InputDecoration(
                          hintText: l10n.searchSalonsHint,
                          prefixIcon: const Icon(Icons.search, size: 22),
                        ),
                      ),
                      const SizedBox(height: 12),
                    ],
                  ),
                ),
              ),
              SliverPadding(
                padding: const EdgeInsets.fromLTRB(20, 0, 20, 24),
                sliver: SliverToBoxAdapter(
                  child: AsyncView<List<Salon>>(
                    value: salons,
                    onRetry: () => ref.invalidate(salonsProvider(_query)),
                    builder: (list) {
                      if (list.isEmpty) {
                        return EmptyState(
                          icon: Icons.storefront_outlined,
                          title: l10n.noSalonsFound,
                          body: l10n.noSalonsFoundBody,
                        );
                      }

                      final width = MediaQuery.sizeOf(context).width;
                      final columns = width >= 1100
                          ? 3
                          : width >= 720
                              ? 2
                              : 1;

                      if (columns == 1) {
                        return Column(
                          children: [
                            for (final (i, salon) in list.indexed) ...[
                              StaggeredItem(
                                index: i,
                                child: _SalonCard(salon: salon),
                              ),
                              const SizedBox(height: 14),
                            ],
                          ],
                        );
                      }

                      return GridView.builder(
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                          crossAxisCount: columns,
                          mainAxisSpacing: 14,
                          crossAxisSpacing: 14,
                          mainAxisExtent: 128,
                        ),
                        itemCount: list.length,
                        itemBuilder: (context, i) => StaggeredItem(
                          index: i,
                          child: _SalonCard(salon: list[i]),
                        ),
                      );
                    },
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _SalonCard extends StatelessWidget {
  const _SalonCard({required this.salon});

  final Salon salon;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Card(
      child: InkWell(
        borderRadius: BorderRadius.circular(20),
        onTap: () => context.go('/salons/${salon.slug}'),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Row(
            children: [
              Hero(
                tag: 'salon-logo-${salon.slug}',
                child: Monogram(
                  text: salon.name.characters.first.toUpperCase(),
                  color: salon.brandColor,
                  size: 56,
                  imageUrl: salon.logoUrl,
                ),
              ),
              const SizedBox(width: 14),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text(
                      salon.name,
                      style: theme.textTheme.titleMedium
                          ?.copyWith(fontWeight: FontWeight.w600),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                    if (salon.address != null && salon.address!.isNotEmpty) ...[
                      const SizedBox(height: 3),
                      Row(
                        children: [
                          const Icon(
                            Icons.location_on_outlined,
                            size: 14,
                            color: Color(0xFF9CA3AF),
                          ),
                          const SizedBox(width: 3),
                          Flexible(
                            child: Text(
                              salon.address!,
                              style: theme.textTheme.bodySmall
                                  ?.copyWith(color: const Color(0xFF6B7280)),
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
              const SizedBox(width: 8),
              Icon(
                Directionality.of(context) == TextDirection.rtl
                    ? Icons.chevron_left
                    : Icons.chevron_right,
                color: const Color(0xFF9CA3AF),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
