// ignore: unused_import
import 'package:intl/intl.dart' as intl;
import 'app_localizations.dart';

// ignore_for_file: type=lint

/// The translations for Arabic (`ar`).
class AppLocalizationsAr extends AppLocalizations {
  AppLocalizationsAr([String locale = 'ar']) : super(locale);

  @override
  String get appTitle => 'صالونات';

  @override
  String get tabSalons => 'الصالونات';

  @override
  String get tabMyBookings => 'حجوزاتي';

  @override
  String get tabSettings => 'الإعدادات';

  @override
  String get salonsTitle => 'اعثر على صالونك';

  @override
  String get salonsSubtitle => 'احجز موعدك القادم في ثوانٍ';

  @override
  String get searchSalonsHint => 'ابحث عن صالون…';

  @override
  String get noSalonsFound => 'لا توجد صالونات';

  @override
  String get noSalonsFoundBody => 'جرّب كلمة بحث مختلفة.';

  @override
  String get viewSalon => 'عرض الصالون';

  @override
  String get servicesSection => 'الخدمات';

  @override
  String get teamSection => 'فريقنا';

  @override
  String get hoursSection => 'أوقات الدوام';

  @override
  String get aboutSection => 'نبذة';

  @override
  String get closedLabel => 'مغلق';

  @override
  String get bookAppointment => 'احجز موعدًا';

  @override
  String get bookThisService => 'احجز';

  @override
  String minutesShort(int count) {
    return '$count دقيقة';
  }

  @override
  String get stepService => 'الخدمة';

  @override
  String get stepStaff => 'الموظف';

  @override
  String get stepDateTime => 'التاريخ والوقت';

  @override
  String get stepDetails => 'بياناتك';

  @override
  String get chooseService => 'اختر الخدمة';

  @override
  String get chooseStaff => 'اختر الموظف';

  @override
  String get anyStaff => 'أي موظف';

  @override
  String get noPreference => 'بدون تفضيل';

  @override
  String get chooseDateTime => 'اختر التاريخ والوقت';

  @override
  String get chooseDate => 'التاريخ';

  @override
  String get availableTimes => 'الأوقات المتاحة';

  @override
  String get selectDateFirst => 'اختر تاريخًا لعرض الأوقات المتاحة.';

  @override
  String get noTimesAvailable =>
      'لا توجد أوقات متاحة في هذا التاريخ. جرّب يومًا آخر.';

  @override
  String get salonClosedThatDay => 'الصالون مغلق في هذا اليوم.';

  @override
  String get yourDetails => 'بياناتك';

  @override
  String get bookingSummary => 'ملخص الحجز';

  @override
  String get fullName => 'الاسم الكامل';

  @override
  String get phoneNumber => 'رقم الهاتف';

  @override
  String get emailOptional => 'البريد الإلكتروني (اختياري)';

  @override
  String get notesOptional => 'ملاحظات (اختياري)';

  @override
  String get notesHint => 'هل لديك طلبات خاصة؟';

  @override
  String get continueLabel => 'متابعة';

  @override
  String get backLabel => 'رجوع';

  @override
  String get confirmBooking => 'تأكيد الحجز';

  @override
  String get nameRequired => 'يرجى إدخال اسمك.';

  @override
  String get phoneRequired => 'يرجى إدخال رقم هاتفك.';

  @override
  String get emailInvalid => 'يرجى إدخال بريد إلكتروني صالح.';

  @override
  String get bookingConfirmedTitle => 'تم إرسال الطلب!';

  @override
  String get bookingConfirmedBody =>
      'سيقوم الصالون بتأكيد موعدك قريبًا. يمكنك متابعة حالته من صفحة حجوزاتي.';

  @override
  String get viewMyBookings => 'عرض حجوزاتي';

  @override
  String get done => 'تم';

  @override
  String get myBookingsTitle => 'حجوزاتي';

  @override
  String get noBookingsYet => 'لا توجد حجوزات بعد';

  @override
  String get noBookingsYetBody => 'ستظهر مواعيدك هنا بعد إتمام الحجز.';

  @override
  String get exploreSalons => 'استكشف الصالونات';

  @override
  String get statusPending => 'قيد الانتظار';

  @override
  String get statusApproved => 'مؤكد';

  @override
  String get statusRejected => 'مرفوض';

  @override
  String get statusCancelled => 'ملغي';

  @override
  String get removeFromList => 'إزالة من القائمة';

  @override
  String get bookingRemoved => 'تمت إزالة الحجز من قائمتك.';

  @override
  String get at => 'الساعة';

  @override
  String get settingsTitle => 'الإعدادات';

  @override
  String get languageSection => 'اللغة';

  @override
  String get english => 'English';

  @override
  String get arabic => 'العربية';

  @override
  String get aboutApp => 'نبذة';

  @override
  String get aboutAppBody => 'احجز مواعيد الصالون بسرعة وسهولة.';

  @override
  String get errorGeneric => 'حدث خطأ ما. يرجى المحاولة مرة أخرى.';

  @override
  String get errorNetwork =>
      'لا يوجد اتصال بالإنترنت. تحقق من الشبكة وحاول مجددًا.';

  @override
  String get retry => 'إعادة المحاولة';

  @override
  String get loading => 'جارٍ التحميل…';
}
