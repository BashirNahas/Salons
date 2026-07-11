/// A booking as returned by the API (creation response and status lookup).
class Booking {
  const Booking({
    required this.id,
    required this.status,
    required this.statusLabel,
    required this.date,
    required this.time,
    required this.customerName,
    this.serviceName,
    this.employeeName,
  });

  final int id;
  final String status; // pending | approved | rejected | cancelled
  final String statusLabel; // localized by the server
  final String date; // Y-m-d
  final String time; // H:i
  final String customerName;
  final String? serviceName;
  final String? employeeName;

  factory Booking.fromJson(Map<String, dynamic> json) {
    final service = json['service'];
    final employee = json['employee'];

    return Booking(
      id: json['id'] as int,
      status: json['status'] as String,
      statusLabel:
          (json['status_label'] as String?) ?? json['status'] as String,
      date: json['date'] as String,
      time: json['time'] as String,
      customerName: json['customer_name'] as String,
      serviceName:
          service is Map<String, dynamic> ? service['name'] as String? : null,
      employeeName:
          employee is Map<String, dynamic> ? employee['name'] as String? : null,
    );
  }
}
