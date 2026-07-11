import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';

import '../../../app/theme.dart';
import '../../../core/api/api_client.dart';
import '../../../core/widgets/async_view.dart';
import '../../../core/widgets/widgets.dart';
import '../../../l10n/gen/app_localizations.dart';
import '../../my_bookings/data/local_bookings_store.dart';
import '../../salons/data/salon_repository.dart';
import '../../salons/domain/models.dart';
import '../../settings/settings_providers.dart';
import '../data/booking_repository.dart';
import '../domain/booking.dart';

class BookingFlowScreen extends ConsumerWidget {
  const BookingFlowScreen({
    super.key,
    required this.slug,
    this.preselectedServiceId,
  });

  final String slug;
  final int? preselectedServiceId;

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final details = ref.watch(salonDetailsProvider(slug));
    final locale = ref.watch(localeProvider);

    return Scaffold(
      body: AsyncView<SalonDetails>(
        value: details,
        onRetry: () => ref.invalidate(salonDetailsProvider(slug)),
        builder: (data) => Theme(
          data: buildTheme(
            arabic: locale.languageCode == 'ar',
            seed: data.salon.brandColor,
          ),
          child: _BookingWizard(
            slug: slug,
            data: data,
            preselectedServiceId: preselectedServiceId,
          ),
        ),
      ),
    );
  }
}

class _BookingWizard extends ConsumerStatefulWidget {
  const _BookingWizard({
    required this.slug,
    required this.data,
    this.preselectedServiceId,
  });

  final String slug;
  final SalonDetails data;
  final int? preselectedServiceId;

  @override
  ConsumerState<_BookingWizard> createState() => _BookingWizardState();
}

class _BookingWizardState extends ConsumerState<_BookingWizard> {
  late final bool hasStaff = widget.data.employees.isNotEmpty;

  int step = 0;
  SalonService? service;
  Employee? employee; // null = any staff
  bool anyStaffChosen = false;
  DateTime? date;
  String? time;

  List<String>? slots;
  bool slotsLoading = false;
  String? slotsError;

  final nameController = TextEditingController();
  final phoneController = TextEditingController();
  final emailController = TextEditingController();
  final notesController = TextEditingController();
  final formKey = GlobalKey<FormState>();

  bool submitting = false;
  Booking? confirmed;

  int get stepCount => hasStaff ? 4 : 3;
  int get dateStep => hasStaff ? 2 : 1;
  int get detailsStep => hasStaff ? 3 : 2;

  @override
  void initState() {
    super.initState();
    final preset = widget.preselectedServiceId;
    if (preset != null) {
      for (final s in widget.data.services) {
        if (s.id == preset) {
          service = s;
          step = 1;
          break;
        }
      }
    }
  }

  @override
  void dispose() {
    nameController.dispose();
    phoneController.dispose();
    emailController.dispose();
    notesController.dispose();
    super.dispose();
  }

  Future<void> _loadSlots() async {
    final selectedService = service;
    final selectedDate = date;
    if (selectedService == null || selectedDate == null) return;

    setState(() {
      slotsLoading = true;
      slotsError = null;
      slots = null;
      time = null;
    });

    try {
      final result = await ref.read(salonRepositoryProvider).slots(
            slug: widget.slug,
            serviceId: selectedService.id,
            date: selectedDate,
            employeeId: employee?.id,
          );
      if (!mounted) return;
      setState(() {
        slots = result;
        slotsLoading = false;
      });
    } on ApiException catch (e) {
      if (!mounted) return;
      setState(() {
        slotsError = e.message;
        slotsLoading = false;
      });
    }
  }

  Future<void> _submit() async {
    final l10n = AppLocalizations.of(context);
    if (!formKey.currentState!.validate()) return;

    final selectedDate = date!;
    final dateString =
        '${selectedDate.year.toString().padLeft(4, '0')}-${selectedDate.month.toString().padLeft(2, '0')}-${selectedDate.day.toString().padLeft(2, '0')}';

    setState(() => submitting = true);

    try {
      final booking = await ref.read(bookingRepositoryProvider).create(
            salonSlug: widget.slug,
            serviceId: service!.id,
            employeeId: employee?.id,
            date: dateString,
            time: time!,
            customerName: nameController.text.trim(),
            customerPhone: phoneController.text.trim(),
            customerEmail: emailController.text.trim(),
            notes: notesController.text.trim(),
          );

      await ref.read(localBookingsProvider.notifier).add(
            BookingRef(
              bookingId: booking.id,
              salonSlug: widget.slug,
              salonName: widget.data.salon.name,
              serviceName: service!.name,
              phone: phoneController.text.trim(),
              date: booking.date,
              time: booking.time,
              createdAtMillis: DateTime.now().millisecondsSinceEpoch,
            ),
          );

      if (!mounted) return;
      setState(() {
        confirmed = booking;
        submitting = false;
      });
    } on ApiException catch (e) {
      if (!mounted) return;
      setState(() => submitting = false);

      final message = e.message == 'network' ? l10n.errorNetwork : e.message;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(message)),
      );

      // Slot conflict: bring the customer back to the time step with
      // fresh availability.
      if (e.statusCode == 422 && e.fieldErrors.containsKey('time')) {
        setState(() => step = dateStep);
        await _loadSlots();
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);

    if (confirmed != null) {
      return _SuccessView(booking: confirmed!, salon: widget.data.salon);
    }

    return Scaffold(
      appBar: AppBar(
        title: Text(l10n.bookAppointment),
        leading: BackButton(
          onPressed: () {
            if (step > 0) {
              setState(() => step--);
            } else {
              context.go('/salons/${widget.slug}');
            }
          },
        ),
      ),
      body: SafeArea(
        child: Center(
          child: ConstrainedBox(
            constraints: const BoxConstraints(maxWidth: 640),
            child: Column(
              children: [
                Padding(
                  padding: const EdgeInsets.fromLTRB(20, 8, 20, 0),
                  child: _StepIndicator(
                    current: step,
                    labels: [
                      l10n.stepService,
                      if (hasStaff) l10n.stepStaff,
                      l10n.stepDateTime,
                      l10n.stepDetails,
                    ],
                  ),
                ),
                Expanded(
                  child: AnimatedSwitcher(
                    duration: const Duration(milliseconds: 240),
                    switchInCurve: Curves.easeOutCubic,
                    transitionBuilder: (child, animation) => FadeTransition(
                      opacity: animation,
                      child: SlideTransition(
                        position: Tween<Offset>(
                          begin: const Offset(0, 0.02),
                          end: Offset.zero,
                        ).animate(animation),
                        child: child,
                      ),
                    ),
                    child: _buildStep(l10n),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildStep(AppLocalizations l10n) {
    if (step == 0) return _serviceStep(l10n);
    if (hasStaff && step == 1) return _staffStep(l10n);
    if (step == dateStep) return _dateTimeStep(l10n);
    return _detailsStep(l10n);
  }

  // ---------- Step 1: service ----------
  Widget _serviceStep(AppLocalizations l10n) {
    final theme = Theme.of(context);

    return ListView(
      key: const ValueKey('service'),
      padding: const EdgeInsets.all(20),
      children: [
        _StepTitle(l10n.chooseService),
        for (final s in widget.data.services) ...[
          _SelectableCard(
            selected: service?.id == s.id,
            onTap: () => setState(() {
              service = s;
              // Changing the service invalidates a previously chosen time.
              time = null;
              slots = null;
            }),
            child: Row(
              children: [
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        s.name,
                        style: theme.textTheme.titleSmall
                            ?.copyWith(fontWeight: FontWeight.w600),
                      ),
                      const SizedBox(height: 3),
                      Text(
                        l10n.minutesShort(s.durationMinutes),
                        style: theme.textTheme.bodySmall
                            ?.copyWith(color: const Color(0xFF6B7280)),
                      ),
                    ],
                  ),
                ),
                if (s.price != null)
                  Text(
                    s.price!.toStringAsFixed(2),
                    style: theme.textTheme.titleSmall?.copyWith(
                      fontWeight: FontWeight.w700,
                      color: theme.colorScheme.primary,
                    ),
                  ),
              ],
            ),
          ),
          const SizedBox(height: 10),
        ],
        const SizedBox(height: 8),
        FilledButton(
          onPressed: service == null ? null : () => setState(() => step = 1),
          child: Text(l10n.continueLabel),
        ),
      ],
    );
  }

  // ---------- Step 2: staff ----------
  Widget _staffStep(AppLocalizations l10n) {
    final width = MediaQuery.sizeOf(context).width;
    final columns = width >= 480 ? 3 : 2;

    return ListView(
      key: const ValueKey('staff'),
      padding: const EdgeInsets.all(20),
      children: [
        _StepTitle(l10n.chooseStaff),
        GridView.count(
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          crossAxisCount: columns,
          mainAxisSpacing: 10,
          crossAxisSpacing: 10,
          childAspectRatio: 0.92,
          children: [
            _StaffCard(
              selected: anyStaffChosen && employee == null,
              name: l10n.anyStaff,
              subtitle: l10n.noPreference,
              avatar: Container(
                width: 56,
                height: 56,
                decoration: BoxDecoration(
                  color: const Color(0xFFF3F4F6),
                  borderRadius: BorderRadius.circular(18),
                ),
                child:
                    const Icon(Icons.groups_outlined, color: Color(0xFF9CA3AF)),
              ),
              onTap: () => setState(() {
                employee = null;
                anyStaffChosen = true;
                time = null;
                slots = null;
              }),
            ),
            for (final e in widget.data.employees)
              _StaffCard(
                selected: employee?.id == e.id,
                name: e.name,
                subtitle: e.specialties,
                avatar: Monogram(
                  text: e.initials,
                  color: widget.data.salon.brandColor,
                  size: 56,
                  imageUrl: e.avatarUrl,
                ),
                onTap: () => setState(() {
                  employee = e;
                  anyStaffChosen = true;
                  time = null;
                  slots = null;
                }),
              ),
          ],
        ),
        const SizedBox(height: 18),
        Row(
          children: [
            Expanded(
              child: OutlinedButton(
                onPressed: () => setState(() => step = 0),
                child: Text(l10n.backLabel),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: FilledButton(
                onPressed: anyStaffChosen
                    ? () => setState(() => step = dateStep)
                    : null,
                child: Text(l10n.continueLabel),
              ),
            ),
          ],
        ),
      ],
    );
  }

  // ---------- Step 3: date & time ----------
  Widget _dateTimeStep(AppLocalizations l10n) {
    final theme = Theme.of(context);
    final today = DateTime.now();
    final days = List.generate(
      14,
      (i) => DateTime(today.year, today.month, today.day + i),
    );

    return ListView(
      key: const ValueKey('datetime'),
      padding: const EdgeInsets.all(20),
      children: [
        _StepTitle(l10n.chooseDateTime),
        SizedBox(
          height: 86,
          child: ListView.separated(
            scrollDirection: Axis.horizontal,
            itemCount: days.length,
            separatorBuilder: (context, i) => const SizedBox(width: 8),
            itemBuilder: (context, i) {
              final day = days[i];
              final closed = widget.data.isClosedOn(day);
              final selected = date != null &&
                  date!.year == day.year &&
                  date!.month == day.month &&
                  date!.day == day.day;

              return _DayChip(
                date: day,
                closed: closed,
                selected: selected,
                onTap: closed
                    ? null
                    : () {
                        setState(() => date = day);
                        _loadSlots();
                      },
              );
            },
          ),
        ),
        const SizedBox(height: 22),
        Text(
          l10n.availableTimes,
          style:
              theme.textTheme.titleSmall?.copyWith(fontWeight: FontWeight.w600),
        ),
        const SizedBox(height: 12),
        if (date == null)
          _HintText(l10n.selectDateFirst)
        else if (slotsLoading)
          const Padding(
            padding: EdgeInsets.symmetric(vertical: 28),
            child: Center(child: CircularProgressIndicator()),
          )
        else if (slotsError != null)
          _HintText(
            slotsError == 'network' ? l10n.errorNetwork : slotsError!,
          )
        else if (slots == null || slots!.isEmpty)
          _HintText(l10n.noTimesAvailable)
        else
          Directionality(
            // Time digits always LTR, even in the Arabic interface.
            textDirection: TextDirection.ltr,
            child: Wrap(
              spacing: 8,
              runSpacing: 8,
              children: [
                for (final slot in slots!)
                  ChoiceChip(
                    label: Text(slot),
                    selected: time == slot,
                    labelStyle: TextStyle(
                      color: time == slot ? Colors.white : null,
                      fontFeatures: const [FontFeature.tabularFigures()],
                    ),
                    onSelected: (selected) =>
                        setState(() => time = selected ? slot : null),
                  ),
              ],
            ),
          ),
        const SizedBox(height: 22),
        Row(
          children: [
            Expanded(
              child: OutlinedButton(
                onPressed: () => setState(() => step = hasStaff ? 1 : 0),
                child: Text(l10n.backLabel),
              ),
            ),
            const SizedBox(width: 12),
            Expanded(
              child: FilledButton(
                onPressed: time == null
                    ? null
                    : () => setState(() => step = detailsStep),
                child: Text(l10n.continueLabel),
              ),
            ),
          ],
        ),
      ],
    );
  }

  // ---------- Step 4: details ----------
  Widget _detailsStep(AppLocalizations l10n) {
    final theme = Theme.of(context);

    final dateLabel = date == null
        ? ''
        : MaterialLocalizations.of(context).formatFullDate(date!);

    return Form(
      key: formKey,
      child: ListView(
        key: const ValueKey('details'),
        padding: const EdgeInsets.all(20),
        children: [
          _StepTitle(l10n.yourDetails),
          Card(
            color: theme.colorScheme.primary.withValues(alpha: 0.06),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(16),
              side: BorderSide(
                color: theme.colorScheme.primary.withValues(alpha: 0.25),
              ),
            ),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    l10n.bookingSummary,
                    style: theme.textTheme.labelLarge?.copyWith(
                      color: theme.colorScheme.primary,
                      fontWeight: FontWeight.w700,
                    ),
                  ),
                  const SizedBox(height: 6),
                  Text(
                    service?.name ?? '',
                    style: theme.textTheme.titleSmall
                        ?.copyWith(fontWeight: FontWeight.w600),
                  ),
                  if (employee != null)
                    Text(
                      employee!.name,
                      style: theme.textTheme.bodySmall
                          ?.copyWith(color: const Color(0xFF6B7280)),
                    ),
                  const SizedBox(height: 2),
                  Text(
                    '$dateLabel · ${l10n.at} ${time ?? ''}',
                    style: theme.textTheme.bodyMedium,
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 18),
          TextFormField(
            controller: nameController,
            textInputAction: TextInputAction.next,
            decoration: InputDecoration(labelText: l10n.fullName),
            validator: (value) => (value == null || value.trim().isEmpty)
                ? l10n.nameRequired
                : null,
          ),
          const SizedBox(height: 12),
          TextFormField(
            controller: phoneController,
            keyboardType: TextInputType.phone,
            textInputAction: TextInputAction.next,
            textDirection: TextDirection.ltr,
            textAlign: Directionality.of(context) == TextDirection.rtl
                ? TextAlign.right
                : TextAlign.left,
            decoration: InputDecoration(
              labelText: l10n.phoneNumber,
              hintText: '+963 9XX XXX XXX',
              hintTextDirection: TextDirection.ltr,
            ),
            validator: (value) => (value == null || value.trim().isEmpty)
                ? l10n.phoneRequired
                : null,
          ),
          const SizedBox(height: 12),
          TextFormField(
            controller: emailController,
            keyboardType: TextInputType.emailAddress,
            textInputAction: TextInputAction.next,
            textDirection: TextDirection.ltr,
            decoration: InputDecoration(labelText: l10n.emailOptional),
            validator: (value) {
              final v = value?.trim() ?? '';
              if (v.isEmpty) return null;
              final ok = RegExp(r'^[^@\s]+@[^@\s]+\.[^@\s]+$').hasMatch(v);
              return ok ? null : l10n.emailInvalid;
            },
          ),
          const SizedBox(height: 12),
          TextFormField(
            controller: notesController,
            maxLines: 3,
            decoration: InputDecoration(
              labelText: l10n.notesOptional,
              hintText: l10n.notesHint,
              alignLabelWithHint: true,
            ),
          ),
          const SizedBox(height: 20),
          Row(
            children: [
              Expanded(
                child: OutlinedButton(
                  onPressed:
                      submitting ? null : () => setState(() => step = dateStep),
                  child: Text(l10n.backLabel),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                flex: 2,
                child: FilledButton(
                  onPressed: submitting ? null : _submit,
                  child: submitting
                      ? const SizedBox(
                          width: 22,
                          height: 22,
                          child: CircularProgressIndicator(
                            strokeWidth: 2.5,
                            color: Colors.white,
                          ),
                        )
                      : Text(l10n.confirmBooking),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

// ---------- Success ----------

class _SuccessView extends StatelessWidget {
  const _SuccessView({required this.booking, required this.salon});

  final Booking booking;
  final Salon salon;

  @override
  Widget build(BuildContext context) {
    final l10n = AppLocalizations.of(context);
    final theme = Theme.of(context);

    return Scaffold(
      body: SafeArea(
        child: Center(
          child: ConstrainedBox(
            constraints: const BoxConstraints(maxWidth: 480),
            child: Padding(
              padding: const EdgeInsets.all(28),
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  TweenAnimationBuilder<double>(
                    tween: Tween(begin: 0, end: 1),
                    duration: const Duration(milliseconds: 550),
                    curve: Curves.elasticOut,
                    builder: (context, t, child) =>
                        Transform.scale(scale: t, child: child),
                    child: Container(
                      width: 96,
                      height: 96,
                      decoration: BoxDecoration(
                        color: const Color(0xFF059669),
                        borderRadius: BorderRadius.circular(32),
                        boxShadow: [
                          BoxShadow(
                            color:
                                const Color(0xFF059669).withValues(alpha: 0.35),
                            blurRadius: 28,
                            offset: const Offset(0, 10),
                          ),
                        ],
                      ),
                      child: const Icon(
                        Icons.check_rounded,
                        color: Colors.white,
                        size: 52,
                      ),
                    ),
                  ),
                  const SizedBox(height: 26),
                  Text(
                    l10n.bookingConfirmedTitle,
                    style: theme.textTheme.headlineSmall
                        ?.copyWith(fontWeight: FontWeight.w700),
                    textAlign: TextAlign.center,
                  ),
                  const SizedBox(height: 8),
                  Text(
                    l10n.bookingConfirmedBody,
                    style: theme.textTheme.bodyMedium
                        ?.copyWith(color: const Color(0xFF6B7280), height: 1.6),
                    textAlign: TextAlign.center,
                  ),
                  const SizedBox(height: 26),
                  Card(
                    child: Padding(
                      padding: const EdgeInsets.all(16),
                      child: Column(
                        children: [
                          _SummaryRow(label: salon.name, bold: true),
                          const Divider(height: 18),
                          _SummaryRow(
                            label: booking.serviceName ?? '',
                            trailing: Directionality(
                              textDirection: TextDirection.ltr,
                              child: Text(
                                '${booking.date} · ${booking.time}',
                                style: theme.textTheme.bodyMedium?.copyWith(
                                  fontFeatures: const [
                                    FontFeature.tabularFigures(),
                                  ],
                                ),
                              ),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                  const SizedBox(height: 26),
                  FilledButton(
                    onPressed: () => context.go('/bookings'),
                    child: Text(l10n.viewMyBookings),
                  ),
                  const SizedBox(height: 10),
                  OutlinedButton(
                    onPressed: () => context.go('/salons'),
                    child: Text(l10n.done),
                  ),
                ],
              ),
            ),
          ),
        ),
      ),
    );
  }
}

class _SummaryRow extends StatelessWidget {
  const _SummaryRow({required this.label, this.trailing, this.bold = false});

  final String label;
  final Widget? trailing;
  final bool bold;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Flexible(
          child: Text(
            label,
            style: theme.textTheme.bodyMedium?.copyWith(
              fontWeight: bold ? FontWeight.w700 : FontWeight.w500,
            ),
            overflow: TextOverflow.ellipsis,
          ),
        ),
        if (trailing != null) trailing!,
      ],
    );
  }
}

// ---------- Small building blocks ----------

class _StepIndicator extends StatelessWidget {
  const _StepIndicator({required this.current, required this.labels});

  final int current;
  final List<String> labels;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Row(
      children: [
        for (final (i, label) in labels.indexed) ...[
          if (i > 0)
            Expanded(
              child: AnimatedContainer(
                duration: const Duration(milliseconds: 250),
                height: 2,
                margin: const EdgeInsets.symmetric(horizontal: 6),
                color: i <= current
                    ? theme.colorScheme.primary
                    : const Color(0xFFE5E7EB),
              ),
            ),
          Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              AnimatedContainer(
                duration: const Duration(milliseconds: 250),
                width: 30,
                height: 30,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: i <= current
                      ? theme.colorScheme.primary
                      : const Color(0xFFE5E7EB),
                ),
                child: Center(
                  child: i < current
                      ? const Icon(Icons.check, size: 16, color: Colors.white)
                      : Text(
                          '${i + 1}',
                          style: TextStyle(
                            fontSize: 12,
                            fontWeight: FontWeight.w700,
                            color: i <= current
                                ? Colors.white
                                : const Color(0xFF6B7280),
                          ),
                        ),
                ),
              ),
              const SizedBox(height: 4),
              Text(
                label,
                style: theme.textTheme.labelSmall?.copyWith(
                  color: i <= current
                      ? theme.colorScheme.primary
                      : const Color(0xFF9CA3AF),
                  fontWeight: FontWeight.w600,
                ),
              ),
            ],
          ),
        ],
      ],
    );
  }
}

class _StepTitle extends StatelessWidget {
  const _StepTitle(this.text);

  final String text;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 14),
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

class _SelectableCard extends StatelessWidget {
  const _SelectableCard({
    required this.selected,
    required this.onTap,
    required this.child,
  });

  final bool selected;
  final VoidCallback onTap;
  final Widget child;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return AnimatedContainer(
      duration: const Duration(milliseconds: 180),
      decoration: BoxDecoration(
        color: selected
            ? theme.colorScheme.primary.withValues(alpha: 0.06)
            : Colors.white,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(
          color: selected ? theme.colorScheme.primary : const Color(0x0D030712),
          width: selected ? 2 : 1,
        ),
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(18),
          onTap: onTap,
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
            child: child,
          ),
        ),
      ),
    );
  }
}

class _StaffCard extends StatelessWidget {
  const _StaffCard({
    required this.selected,
    required this.name,
    required this.avatar,
    required this.onTap,
    this.subtitle,
  });

  final bool selected;
  final String name;
  final String? subtitle;
  final Widget avatar;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return AnimatedContainer(
      duration: const Duration(milliseconds: 180),
      decoration: BoxDecoration(
        color: selected
            ? theme.colorScheme.primary.withValues(alpha: 0.06)
            : Colors.white,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(
          color: selected ? theme.colorScheme.primary : const Color(0x0D030712),
          width: selected ? 2 : 1,
        ),
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(18),
          onTap: onTap,
          child: Padding(
            padding: const EdgeInsets.all(10),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                avatar,
                const SizedBox(height: 8),
                Text(
                  name,
                  style: theme.textTheme.bodySmall
                      ?.copyWith(fontWeight: FontWeight.w600),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                  textAlign: TextAlign.center,
                ),
                if (subtitle != null && subtitle!.isNotEmpty)
                  Text(
                    subtitle!,
                    style: theme.textTheme.labelSmall
                        ?.copyWith(color: const Color(0xFF9CA3AF)),
                    maxLines: 1,
                    overflow: TextOverflow.ellipsis,
                    textAlign: TextAlign.center,
                  ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

class _DayChip extends StatelessWidget {
  const _DayChip({
    required this.date,
    required this.closed,
    required this.selected,
    required this.onTap,
  });

  final DateTime date;
  final bool closed;
  final bool selected;
  final VoidCallback? onTap;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final material = MaterialLocalizations.of(context);
    final weekday =
        material.narrowWeekdays[date.weekday % 7]; // Sun=0 in narrowWeekdays

    final foreground = closed
        ? const Color(0xFFD1D5DB)
        : selected
            ? Colors.white
            : const Color(0xFF374151);

    return AnimatedContainer(
      duration: const Duration(milliseconds: 180),
      width: 62,
      decoration: BoxDecoration(
        color: selected ? theme.colorScheme.primary : Colors.white,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(
          color: selected ? theme.colorScheme.primary : const Color(0xFFE5E7EB),
        ),
      ),
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          borderRadius: BorderRadius.circular(16),
          onTap: onTap,
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Text(
                weekday,
                style: theme.textTheme.labelSmall?.copyWith(
                  color: selected ? Colors.white70 : const Color(0xFF9CA3AF),
                  fontWeight: FontWeight.w600,
                ),
              ),
              const SizedBox(height: 4),
              Text(
                '${date.day}',
                style: theme.textTheme.titleMedium?.copyWith(
                  color: foreground,
                  fontWeight: FontWeight.w700,
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _HintText extends StatelessWidget {
  const _HintText(this.text);

  final String text;

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Text(
        text,
        style: Theme.of(context)
            .textTheme
            .bodyMedium
            ?.copyWith(color: const Color(0xFF6B7280)),
      ),
    );
  }
}
