import 'package:flutter/material.dart';

class GuestSelector extends StatefulWidget {
  final int initialAdults;
  final int initialChildren;
  final int initialRooms;
  final Function(int adults, int children, int rooms) onChanged;
  final int? maxRooms;
  final int maxGuestsPerRoom;

  const GuestSelector({
    Key? key,
    this.initialAdults = 1,
    this.initialChildren = 0,
    this.initialRooms = 1,
    required this.onChanged,
    this.maxRooms,
    this.maxGuestsPerRoom = 4,
  }) : super(key: key);

  @override
  _GuestSelectorState createState() => _GuestSelectorState();
}

class _GuestSelectorState extends State<GuestSelector> {
  late int _adults;
  late int _children;
  late int _rooms;

  @override
  void initState() {
    super.initState();
    _adults = widget.initialAdults;
    _children = widget.initialChildren;
    _rooms = widget.initialRooms;
  }

  void _updateCount(String type, {bool increment = true}) {
    setState(() {
      switch (type) {
        case 'adults':
          _adults = increment ? _adults + 1 : (_adults > 1 ? _adults - 1 : 1);
          break;
        case 'children':
          _children = increment
              ? _children + 1
              : (_children > 0 ? _children - 1 : 0);
          break;
        case 'rooms':
          final maxRooms = widget.maxRooms ?? 10;
          _rooms = increment
              ? (_rooms < maxRooms ? _rooms + 1 : _rooms)
              : (_rooms > 1 ? _rooms - 1 : 1);
          break;
      }
      widget.onChanged(_adults, _children, _rooms);
    });
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text(
          'Guests & Rooms',
          style: TextStyle(fontWeight: FontWeight.w500, fontSize: 14),
        ),
        const SizedBox(height: 8),
        Container(
          decoration: BoxDecoration(
            border: Border.all(color: Colors.grey[300]!),
            borderRadius: BorderRadius.circular(8),
          ),
          child: Column(
            children: [
              _buildCounterRow('Adults', _adults, 'adults'),
              const Divider(height: 1, thickness: 1),
              _buildCounterRow('Children', _children, 'children'),
              const Divider(height: 1, thickness: 1),
              _buildCounterRow(
                'Rooms',
                _rooms,
                'rooms',
                max: widget.maxRooms,
              ),
            ],
          ),
        ),
      ],
    );
  }

  Widget _buildCounterRow(String label, int value, String type, {int? max}) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                label,
                style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w500),
              ),
              if (type == 'children')
                const Text(
                  'Ages 0-12',
                  style: TextStyle(fontSize: 12, color: Colors.grey),
                ),
            ],
          ),
          Row(
            children: [
              _buildCounterButton(
                icon: Icons.remove,
                onPressed: () => _updateCount(type, increment: false),
                isEnabled: value > (type == 'adults' ? 1 : 0),
              ),
              Container(
                width: 30,
                alignment: Alignment.center,
                child: Text(
                  '$value',
                  style: const TextStyle(
                    fontSize: 16,
                    fontWeight: FontWeight.w500,
                  ),
                ),
              ),
              _buildCounterButton(
                icon: Icons.add,
                onPressed: () {
                  if (max != null && value >= max) return;
                  if (type != 'rooms' &&
                      _adults + _children >= _rooms * widget.maxGuestsPerRoom) {
                    ScaffoldMessenger.of(context).showSnackBar(
                      SnackBar(
                        content: Text(
                            'Maximum ${_rooms * widget.maxGuestsPerRoom} guests per booking'),
                        behavior: SnackBarBehavior.floating,
                      ),
                    );
                    return;
                  }
                  _updateCount(type, increment: true);
                },
                isEnabled: max == null || value < max,
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildCounterButton({
    required IconData icon,
    required VoidCallback onPressed,
    required bool isEnabled,
  }) {
    return IconButton(
      icon: Icon(icon, size: 20),
      onPressed: isEnabled ? onPressed : null,
      style: IconButton.styleFrom(
        padding: EdgeInsets.zero,
        minimumSize: const Size(36, 36),
        tapTargetSize: MaterialTapTargetSize.shrinkWrap,
      ),
      color: isEnabled ? Colors.orange : Colors.grey[400],
    );
  }
}
