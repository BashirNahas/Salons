// ignore: unused_import
import 'package:intl/intl.dart' as intl;
import 'app_localizations.dart';

// ignore_for_file: type=lint

/// The translations for English (`en`).
class AppLocalizationsEn extends AppLocalizations {
  AppLocalizationsEn([String locale = 'en']) : super(locale);

  @override
  String get appTitle => 'Salons';

  @override
  String get tabSalons => 'Salons';

  @override
  String get tabMyBookings => 'My Bookings';

  @override
  String get tabSettings => 'Settings';

  @override
  String get salonsTitle => 'Find your salon';

  @override
  String get salonsSubtitle => 'Book your next appointment in seconds';

  @override
  String get searchSalonsHint => 'Search salons…';

  @override
  String get noSalonsFound => 'No salons found';

  @override
  String get noSalonsFoundBody => 'Try a different search term.';

  @override
  String get viewSalon => 'View salon';

  @override
  String get servicesSection => 'Services';

  @override
  String get teamSection => 'Our Team';

  @override
  String get hoursSection => 'Opening Hours';

  @override
  String get aboutSection => 'About';

  @override
  String get closedLabel => 'Closed';

  @override
  String get bookAppointment => 'Book Appointment';

  @override
  String get bookThisService => 'Book';

  @override
  String minutesShort(int count) {
    return '$count min';
  }

  @override
  String get stepService => 'Service';

  @override
  String get stepStaff => 'Staff';

  @override
  String get stepDateTime => 'Date & Time';

  @override
  String get stepDetails => 'Your Info';

  @override
  String get chooseService => 'Choose a service';

  @override
  String get chooseStaff => 'Choose a staff member';

  @override
  String get anyStaff => 'Any staff';

  @override
  String get noPreference => 'No preference';

  @override
  String get chooseDateTime => 'Pick a date & time';

  @override
  String get chooseDate => 'Date';

  @override
  String get availableTimes => 'Available times';

  @override
  String get selectDateFirst => 'Select a date to see available times.';

  @override
  String get noTimesAvailable =>
      'No available times for this date. Try another day.';

  @override
  String get salonClosedThatDay => 'The salon is closed on this day.';

  @override
  String get yourDetails => 'Your details';

  @override
  String get bookingSummary => 'Booking summary';

  @override
  String get fullName => 'Full name';

  @override
  String get phoneNumber => 'Phone number';

  @override
  String get emailOptional => 'Email (optional)';

  @override
  String get notesOptional => 'Notes (optional)';

  @override
  String get notesHint => 'Any special requests?';

  @override
  String get continueLabel => 'Continue';

  @override
  String get backLabel => 'Back';

  @override
  String get confirmBooking => 'Confirm Booking';

  @override
  String get nameRequired => 'Please enter your name.';

  @override
  String get phoneRequired => 'Please enter your phone number.';

  @override
  String get emailInvalid => 'Please enter a valid email address.';

  @override
  String get bookingConfirmedTitle => 'Request sent!';

  @override
  String get bookingConfirmedBody =>
      'The salon will confirm your appointment shortly. You can follow its status in My Bookings.';

  @override
  String get viewMyBookings => 'View My Bookings';

  @override
  String get done => 'Done';

  @override
  String get myBookingsTitle => 'My Bookings';

  @override
  String get noBookingsYet => 'No bookings yet';

  @override
  String get noBookingsYetBody =>
      'Your appointments will appear here after you book.';

  @override
  String get exploreSalons => 'Explore salons';

  @override
  String get statusPending => 'Pending';

  @override
  String get statusApproved => 'Confirmed';

  @override
  String get statusRejected => 'Rejected';

  @override
  String get statusCancelled => 'Cancelled';

  @override
  String get removeFromList => 'Remove from list';

  @override
  String get bookingRemoved => 'Booking removed from your list.';

  @override
  String get at => 'at';

  @override
  String get settingsTitle => 'Settings';

  @override
  String get languageSection => 'Language';

  @override
  String get english => 'English';

  @override
  String get arabic => 'العربية';

  @override
  String get aboutApp => 'About';

  @override
  String get aboutAppBody => 'Book salon appointments quickly and easily.';

  @override
  String get errorGeneric => 'Something went wrong. Please try again.';

  @override
  String get errorNetwork =>
      'No internet connection. Check your network and try again.';

  @override
  String get retry => 'Retry';

  @override
  String get loading => 'Loading…';
}
