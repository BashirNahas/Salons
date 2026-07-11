# Salons — Flutter Mobile App

Customer-facing booking app for the Salons System platform (Android first,
iOS-ready). Connects to the Laravel backend through the JSON API under
`/api/v1`.

## Features

- Salon discovery with search
- Salon profile: services, team, opening hours — themed with each salon's
  own brand color
- 4-step booking flow (service → staff → date & time → details) with live
  slot availability
- My Bookings: on-device booking history with live status (pending /
  confirmed / rejected / cancelled) — no customer account needed
- Full Arabic + English localization with automatic RTL
- Responsive: phones, foldables, and tablets (navigation rail ≥ 800 px)

## Architecture

- **Feature-based structure** under `lib/features/` (salons, booking,
  my_bookings, settings), each split into `domain` / `data` /
  `presentation`
- **Riverpod** for state management, **go_router** for navigation,
  **dio** for networking, **shared_preferences** for local persistence
- No business logic in the app — availability and booking rules live on
  the server; the app consumes them

## Building

Requirements: Flutter 3.24+ (stable channel).

```bash
flutter pub get
flutter gen-l10n        # generates lib/l10n/gen from the .arb files
flutter run             # debug on a connected device
```

Release APK (defaults to the production API at salons.synaptix.sy):

```bash
flutter build apk --release
```

Point the app at a different backend:

```bash
flutter build apk --release --dart-define=API_BASE_URL=https://your-host/api/v1
```

The signed-release setup (keystore + `key.properties`) follows the
standard Flutter docs before a Play Store upload.

## Backend endpoints used

| Method | Path                                   | Purpose                          |
| ------ | -------------------------------------- | -------------------------------- |
| GET    | /api/v1/salons?q=                       | Salon directory + search         |
| GET    | /api/v1/salons/{slug}                   | Profile, services, staff, hours  |
| GET    | /api/v1/salons/{slug}/slots             | Available times for a date       |
| POST   | /api/v1/salons/{slug}/bookings          | Create booking request (pending) |
| GET    | /api/v1/salons/{slug}/bookings/{id}     | Status lookup (phone-verified)   |

All responses are localized by the `Accept-Language` header the app sends.
