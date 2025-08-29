import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_dotenv/flutter_dotenv.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';

import 'screens/register.dart';
import 'screens/login.dart';
import 'screens/onboarding.dart';
import 'screens/home_screen.dart';
import 'screens/payment_screen.dart';
import 'screens/profile_screen.dart';
import 'screens/message_screen.dart';
import 'screens/splash_screen.dart';
import 'services/user_service.dart';
import 'providers/auth_provider.dart'; // 

Future<void> main() async {
  WidgetsFlutterBinding.ensureInitialized();

  // Load environment variables
  await dotenv.load(fileName: ".env");

  // Initialize SharedPreferences
  final prefs = await SharedPreferences.getInstance();

  // Initialize UserService
  await UserService.init();

  // Lock screen orientation
  await SystemChrome.setPreferredOrientations([
    DeviceOrientation.portraitUp,
    DeviceOrientation.portraitDown,
  ]);

  runApp(
    MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AuthProvider(prefs)),
      ],
      child: const TravelBookingApp(),
    ),
  );
}

class TravelBookingApp extends StatelessWidget {
  const TravelBookingApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Booking Travel',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        primarySwatch: Colors.blue,
        fontFamily: 'Roboto',
        visualDensity: VisualDensity.adaptivePlatformDensity,
        appBarTheme: const AppBarTheme(
          elevation: 0,
          backgroundColor: Colors.transparent,
          systemOverlayStyle: SystemUiOverlayStyle.light,
        ),
      ),
      home: Consumer<AuthProvider>(
        builder: (context, authProvider, _) {
          // Show splash screen while initializing
          if (!authProvider.isInitialized) {
            return const SplashScreen();
          }
          
          // Check authentication status and redirect accordingly
          if (authProvider.isAuthenticated) {
            return const HomeScreen(); 
          } else {
            return OnboardingScreen();
          }
        },
      ),
      routes: {
        '/login': (context) => const LoginScreen(),
        '/register': (context) => const RegisterScreen(),
        '/onboarding': (context) => OnboardingScreen(),
        '/home': (context) => HomeScreen(),
        '/payment': (context) => PaymentScreen(),
        '/message': (context) => MessageScreen(),
        '/profile': (context) => ProfileScreen(),
      },
    );
  }
}

// Rest of the code remains the same
