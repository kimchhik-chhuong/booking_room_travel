import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

class DateRangePickerField extends StatelessWidget {
  final DateTime initialStartDate;
  final DateTime initialEndDate;
  final Function(DateTime start, DateTime end) onDatesSelected;
  final DateTime? firstDate;
  final DateTime? lastDate;

  const DateRangePickerField({
    Key? key,
    required this.initialStartDate,
    required this.initialEndDate,
    required this.onDatesSelected,
    this.firstDate,
    this.lastDate,
  }) : super(key: key);

  Future<void> _selectDateRange(BuildContext context) async {
    final DateTimeRange? picked = await showDateRangePicker(
      context: context,
      firstDate: firstDate ?? DateTime.now(),
      lastDate: lastDate ?? DateTime.now().add(const Duration(days: 365)),
      initialDateRange: DateTimeRange(
        start: initialStartDate,
        end: initialEndDate,
      ),
      builder: (context, child) {
        return Theme(
          data: Theme.of(context).copyWith(
            colorScheme: ColorScheme.light(
              primary: Theme.of(context).primaryColor,
              onPrimary: Colors.white,
              surface: Colors.white,
              onSurface: Colors.black,
            ),
          ),
          child: child!,
        );
      },
    );

    if (picked != null) {
      onDatesSelected(picked.start, picked.end);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text(
          'Dates',
          style: TextStyle(
            fontSize: 16,
            fontWeight: FontWeight.bold,
          ),
        ),
        const SizedBox(height: 8),
        InkWell(
          onTap: () => _selectDateRange(context),
          child: Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              border: Border.all(color: Colors.grey[300]!),
              borderRadius: BorderRadius.circular(8),
            ),
            child: Row(
              children: [
                const Icon(Icons.calendar_today, size: 20, color: Colors.grey),
                const SizedBox(width: 16),
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      '${DateFormat('MMM d, yyyy').format(initialStartDate)} - ${DateFormat('MMM d, yyyy').format(initialEndDate)}',
                      style: const TextStyle(fontSize: 16),
                    ),
                    const SizedBox(height: 4),
                    Text(
                      '${initialEndDate.difference(initialStartDate).inDays} nights',
                      style: TextStyle(
                        fontSize: 14,
                        color: Colors.grey[600],
                      ),
                    ),
                  ],
                ),
                const Spacer(),
                const Icon(Icons.arrow_forward_ios, size: 16, color: Colors.grey),
              ],
            ),
          ),
        ),
      ],
    );
  }
}
