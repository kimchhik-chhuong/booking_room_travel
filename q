[33mcommit 02531e2b060c21442e2464be8a822dbef98cead7[m[33m ([m[1;36mHEAD[m[33m -> [m[1;32mtrip[m[33m, [m[1;31morigin/trip[m[33m)[m
Author: yon ratana <yonratana71@gmail.com>
Date:   Tue Jul 29 15:46:32 2025 +0700

    Add real files

--

[33mcommit bdaee4218f528ad95f7070792926279f9e0695ee[m
Author: yon ratana <yonratana71@gmail.com>
Date:   Tue Jul 29 15:17:42 2025 +0700

    Your descriptive message about the changes

booking_travel_api/database/migrations/2025_07_28_033923_add_role_to_users_table.php
booking_travel_api/database/migrations/2025_07_28_034146_create_messages_table.php

[33mcommit 56bc5ab9fedbf4ee1017a71c27ffc883fbcc358a[m
Author: yon ratana <yonratana71@gmail.com>
Date:   Tue Jul 29 14:56:03 2025 +0700

    Recovered files from previous state

booking_travel_flutter/lib/assets/images/Bantaymeanchey.jpg
booking_travel_flutter/lib/assets/images/Kep.jpg
booking_travel_flutter/lib/assets/images/Preyveng.jpg
booking_travel_flutter/lib/assets/images/Pursat.jpeg
booking_travel_flutter/lib/assets/images/batambong.jpg
booking_travel_flutter/lib/assets/images/kaldal.jpg
booking_travel_flutter/lib/assets/images/kampongchhnang.jpg
booking_travel_flutter/lib/assets/images/kampongspue.jpg
booking_travel_flutter/lib/assets/images/kampot.jpg
booking_travel_flutter/lib/assets/images/kohkong.jpg
booking_travel_flutter/lib/assets/images/kompong-thom.jpg
booking_travel_flutter/lib/assets/images/kompongcham.jpg
booking_travel_flutter/lib/assets/images/krojes.jpg
booking_travel_flutter/lib/assets/images/mundoulkiri.jpg
booking_travel_flutter/lib/assets/images/oddar-Meanchey.jpg
booking_travel_flutter/lib/assets/images/pailin.jpg
booking_travel_flutter/lib/assets/images/phnompenh.jpg
booking_travel_flutter/lib/assets/images/preakvihea.jpg
booking_travel_flutter/lib/assets/images/pursat.jpg
booking_travel_flutter/lib/assets/images/svayrieng.jpg
booking_travel_flutter/lib/assets/images/syhanus.jpg
booking_travel_flutter/lib/assets/images/takeo.jpg
booking_travel_flutter/lib/assets/images/tbumkmum.png
booking_travel_flutter/lib/screens/home_screen.dart
booking_travel_flutter/lib/screens/trips/banteaymeanchey.dart
booking_travel_flutter/lib/screens/trips/battambang.dart
booking_travel_flutter/lib/screens/trips/kampongcham.dart
booking_travel_flutter/lib/screens/trips/kampongchhnang.dart
booking_travel_flutter/lib/screens/trips/kampongspeu.dart
booking_travel_flutter/lib/screens/trips/kampongthom.dart
booking_travel_flutter/lib/screens/trips/kandal.dart
booking_travel_flutter/lib/screens/trips/kep.dart
booking_travel_flutter/lib/screens/trips/kohkong.dart
booking_travel_flutter/lib/screens/trips/kompot.dart
booking_travel_flutter/lib/screens/trips/mondulkiri.dart
booking_travel_flutter/lib/screens/trips/oddarmeanchey.dart
booking_travel_flutter/lib/screens/trips/pailin.dart
booking_travel_flutter/lib/screens/trips/phnompenh.dart
booking_travel_flutter/lib/screens/trips/preahvihear.dart
booking_travel_flutter/lib/screens/trips/preyveng.dart
booking_travel_flutter/lib/screens/trips/pursat.dart
booking_travel_flutter/lib/screens/trips/ratanakiri.dart
booking_travel_flutter/lib/screens/trips/siemreap.dart
booking_travel_flutter/lib/screens/trips/sihanoukville.dart
booking_travel_flutter/lib/screens/trips/stugteang.dart
booking_travel_flutter/lib/screens/trips/svayrieng.dart
booking_travel_flutter/lib/screens/trips/takeo.dart
booking_travel_flutter/lib/screens/trips/tboungkhmum.dart

[33mcommit 8a0c34c5e25b61272d804ffd2767f7ab56e3fe67[m
Author: yon ratana <yonratana71@gmail.com>
Date:   Tue Jul 29 14:41:25 2025 +0700

    trip[TRAVEL-24]

booking_travel_api/database/migrations/2025_07_28_033923_add_role_to_users_table.php
booking_travel_api/database/migrations/2025_07_28_034146_create_messages_table.php
booking_travel_flutter/lib/assets/images/Bantaymeanchey.jpg
booking_travel_flutter/lib/assets/images/Kep.jpg
booking_travel_flutter/lib/assets/images/Preyveng.jpg
booking_travel_flutter/lib/assets/images/Pursat.jpeg
booking_travel_flutter/lib/assets/images/batambong.jpg
booking_travel_flutter/lib/assets/images/kaldal.jpg
booking_travel_flutter/lib/assets/images/kampongchhnang.jpg
booking_travel_flutter/lib/assets/images/kampongspue.jpg
booking_travel_flutter/lib/assets/images/kampot.jpg
booking_travel_flutter/lib/assets/images/kohkong.jpg
booking_travel_flutter/lib/assets/images/kompong-thom.jpg
booking_travel_flutter/lib/assets/images/kompongcham.jpg
booking_travel_flutter/lib/assets/images/krojes.jpg
booking_travel_flutter/lib/assets/images/mundoulkiri.jpg
booking_travel_flutter/lib/assets/images/oddar-Meanchey.jpg
booking_travel_flutter/lib/assets/images/pailin.jpg
booking_travel_flutter/lib/assets/images/phnompenh.jpg
booking_travel_flutter/lib/assets/images/preakvihea.jpg
booking_travel_flutter/lib/assets/images/pursat.jpg
booking_travel_flutter/lib/assets/images/svayrieng.jpg
booking_travel_flutter/lib/assets/images/syhanus.jpg
booking_travel_flutter/lib/assets/images/takeo.jpg
booking_travel_flutter/lib/assets/images/tbumkmum.png
booking_travel_flutter/lib/screens/home_screen.dart
booking_travel_flutter/lib/screens/page/trips_page.dart
booking_travel_flutter/lib/screens/trips/banteaymeanchey.dart
booking_travel_flutter/lib/screens/trips/battambang.dart
booking_travel_flutter/lib/screens/trips/kampongcham.dart
booking_travel_flutter/lib/screens/trips/kampongchhnang.dart
booking_travel_flutter/lib/screens/trips/kampongspeu.dart
booking_travel_flutter/lib/screens/trips/kampongthom.dart
booking_travel_flutter/lib/screens/trips/kandal.dart
booking_travel_flutter/lib/screens/trips/kep.dart
booking_travel_flutter/lib/screens/trips/kohkong.dart
booking_travel_flutter/lib/screens/trips/kompot.dart
booking_travel_flutter/lib/screens/trips/mondulkiri.dart
booking_travel_flutter/lib/screens/trips/oddarmeanchey.dart
booking_travel_flutter/lib/screens/trips/pailin.dart
booking_travel_flutter/lib/screens/trips/phnompenh.dart
booking_travel_flutter/lib/screens/trips/preahvihear.dart
booking_travel_flutter/lib/screens/trips/preyveng.dart
booking_travel_flutter/lib/screens/trips/pursat.dart
booking_travel_flutter/lib/screens/trips/ratanakiri.dart
booking_travel_flutter/lib/screens/trips/siemreap.dart
booking_travel_flutter/lib/screens/trips/sihanoukville.dart
booking_travel_flutter/lib/screens/trips/stugteang.dart
booking_travel_flutter/lib/screens/trips/svayrieng.dart
booking_travel_flutter/lib/screens/trips/takeo.dart
booking_travel_flutter/lib/screens/trips/tboungkhmum.dart
booking_travel_flutter/pubspec.yaml

[33mcommit fadcea5e005fee23df6ce4adf6329e0cd0435cdc[m[33m ([m[1;31morigin/payment[m[33m, [m[1;31morigin/notification[m[33m)[m
Merge: 59b540f e3ccfc6
Author: kimchhik-chhuong <kimchhik.chhuong@student.passerellesnumeriques.org>
Date:   Mon Jul 28 15:48:42 2025 +0700

    Merge branch 'main' into payment

[33mcommit 59b540fcc75ddb12656d7bd39327fdbe327d92c1[m
Author: kimchhik-chhuong <kimchhik.chhuong@student.passerellesnumeriques.org>
Date:   Mon Jul 28 15:46:29 2025 +0700

    add image[TRAVEL-39]

booking_travel_flutter/lib/screens/home_screen.dart

[33mcommit e3ccfc6011ec89a85971832daf32e9d902f701bf[m[33m ([m[1;31morigin/receipt[m[33m)[m
Author: sreyletsao <sreylet.sao@student.passerellesnumeriques.org>
Date:   Mon Jul 28 15:25:22 2025 +0700

    update receipt make it better [travel:40]

booking_travel_flutter/lib/screens/payment_screen.dart

[33mcommit afb04a535b1b8616372e8468680ff9999253b3b2[m
Merge: 4b3fca2 f8d1ca9
Author: sreyletsao <sreylet.sao@student.passerellesnumeriques.org>
Date:   Mon Jul 28 15:10:25 2025 +0700

    save local changes before merging main

[33mcommit 4b3fca2f6b875c9a081d6541f0fa2b56f13fd223[m
Author: sreyletsao <sreylet.sao@student.passerellesnumeriques.org>
Date:   Mon Jul 28 14:16:50 2025 +0700

    make download receipt [TRAVEL-31]

booking_travel_flutter/android/app/src/main/AndroidManifest.xml
booking_travel_flutter/ios/Runner/Info.plist
booking_travel_flutter/lib/screens/payment_screen.dart
booking_travel_flutter/macos/Flutter/GeneratedPluginRegistrant.swift
booking_travel_flutter/pubspec.lock
booking_travel_flutter/pubspec.yaml
booking_travel_flutter/windows/flutter/generated_plugin_registrant.cc
booking_travel_flutter/windows/flutter/generated_plugins.cmake

[33mcommit f8d1ca9e2180ddebda680bff50f7a47b4910aefd[m[33m ([m[1;31morigin/room_detail[m[33m, [m[1;31morigin/messages[m[33m)[m
Merge: b7e175a 2933dfe
Author: Kimchhik Chhuong <kimchhik.chhuong@student.passerellesnumeriques.org>
Date:   Mon Jul 28 13:30:07 2025 +0700

    Resolve merge conflict and complete merge from origin/main

[33mcommit b7e175aaee333628ad8fee29c2771fe37505167c[m
Author: Kimchhik Chhuong <kimchhik.chhuong@student.passerellesnumeriques.org>
Date:   Mon Jul 28 13:20:22 2025 +0700

    add code latouts, partials, dashboade[TRAVEL-28]

booking_travel_api/app/Http/Controllers/DashboardController.php
booking_travel_api/app/Models/Booking.php
booking_travel_api/app/Models/Message.php
booking_travel_api/app/Models/Package.php
booking_travel_api/database/migrations/2025_07_21_023844_create_destinations_table.php
booking_travel_api/database/migrations/2025_07_21_023910_create_payments_table.php
booking_travel_api/database/migrations/2025_07_21_023920_create_notifications_table.php
booking_travel_api/database/migrations/2025_07_28_025215_create_messages_table.php
booking_travel_api/database/migrations/2025_07_28_030919_create_packages_table.php
booking_travel_api/database/migrations/2025_07_28_031111_create_bookings_table.php
booking_travel_api/database/migrations/2025_07_28_031302_create_payments_table.php
booking_travel_api/database/migrations/2025_07_28_032214_create_notifications_table.php
booking_travel_api/database/migrations/2025_07_28_032359_create_travelers_table.php
booking_travel_api/database/migrations/2025_07_28_032644_create_hotel_bookings_table.php
booking_travel_api/database/migrations/2025_07_28_033811_create_reviews_table.php
booking_travel_api/database/migrations/2025_07_28_033841_create_personal_access_tokens_table.php
booking_travel_api/database/migrations/2025_07_28_033923_add_role_to_users_table.php
booking_travel_api/database/migrations/2025_07_28_034146_create_messages_table.php
booking_travel_api/resources/views/dashboard.blade.php
booking_travel_api/resources/views/layouts/dashboard.blade.php
booking_travel_api/resources/views/partials/header.blade.php
booking_travel_api/resources/views/partials/right-sidebar.blade.php
booking_travel_api/resources/views/partials/sidebar.blade.php
booking_travel_api/routes/web.php

[33mcommit 2933dfe0eb7a881a37b30f568f3bd8d7c7da8051[m
Merge: 983b1f7 41a1537
Author: kimchhik-chhuong <kimchhik.chhuong@student.passerellesnumeriques.org>
Date:   Mon Jul 28 10:55:04 2025 +0700

    Merge branch 'main' into payment

[33mcommit 41a1537eec52af189635c2d5af758b7e7b654d7f[m
Merge: 6d7ed4f 2f0091a
Author: kimchhik-chhuong <kimchhik.chhuong@student.passerellesnumeriques.org>
Date:   Mon Jul 28 10:54:13 2025 +0700

    Resolved merge conflicts in API

[33mcommit 983b1f7ab16df10b3387a8850be6cb663f753504[m
Author: kimchhik-chhuong <kimchhik.chhuong@student.passerellesnumeriques.org>
Date:   Mon Jul 28 10:38:41 2025 +0700

    add image[TRAVEL-39]

booking_travel_flutter/lib/assets/f1.png
booking_travel_flutter/lib/assets/f2.png
booking_travel_flutter/lib/assets/f3.png
booking_travel_flutter/lib/screens/onboarding.dart
booking_travel_flutter/pubspec.yaml

[33mcommit 524ade4c386afe9755898ec1f16972460e295955[m
Author: Kimchhik Chhuong <kimchhik.chhuong@student.passerellesnumeriques.org>
Date:   Mon Jul 28 08:55:04 2025 +0700

    fix code in main[TRAVEL-28]

booking_travel_api/.env.example

[33mcommit 6d58482391107f227118c92f7f1f0dee182193e7[m
Author: sreyletsao <sreylet.sao@student.passerellesnumeriques.org>
Date:   Mon Jul 28 08:21:08 2025 +0700

    fix

booking_travel_api/app/Http/Controllers/UserController.php
booking_travel_api/routes/api.php

[33mcommit 2f0091a39a0ccc427c0925f3f6f935f14795fbcb[m
Author: sreyletsao <sreylet.sao@student.passerellesnumeriques.org>
Date:   Mon Jul 28 08:16:50 2025 +0700

    fix

booking_travel_api/app/Http/Controllers/UserController.php
booking_travel_api/routes/api.php

[33mcommit b99d57bb3acc991d6a3d94d2f6bc1727e4824d51[m
Author: sreyletsao <sreylet.sao@student.passerellesnumeriques.org>
Date:   Mon Jul 28 08:14:21 2025 +0700

    fix

booking_travel_api/app/Http/Controllers/UserController.php.orig
booking_travel_api/routes/api.php.orig

[33mcommit 6024a3364204b29d59cd3655a8c2f826fffa6e94[m
Author: sreyletsao <sreylet.sao@student.passerellesnumeriques.org>
Date:   Mon Jul 28 08:02:42 2025 +0700

    merge code

booking_travel_flutter/pubspec.yaml

[33mcommit 901dcbcabd60212254462dc2670d43f825040318[m
Merge: 07a1ff5 7645a1c
Author: sreyletsao <sreylet.sao@student.passerellesnumeriques.org>
Date:   Mon Jul 28 07:51:15 2025 +0700

    merge code

[33mcommit 07a1ff5cffe74414a433495344ed01a350b1bf07[m
Merge: 3f61ddc be0c5c2
Author: sreyletsao <sreylet.sao@student.passerellesnumeriques.org>
Date:   Mon Jul 28 07:48:45 2025 +0700

    merge code

[33mcommit 3f61ddc7d7b3d91688d2c6f8bafa22be7f6266c7[m
Author: sreyletsao <sreylet.sao@student.passerellesnumeriques.org>
Date:   Mon Jul 28 07:45:39 2025 +0700

    get form logout from api

booking_travel_flutter/lib/screens/payment_screen.dart
booking_travel_flutter/pubspec.yaml
booking_travel_flutter/test/widget_test.dart

[33mcommit 7645a1c70dcd1ab92767a37601b2db9d43d2d9f4[m
Author: sreyletsao <sreylet.sao@student.passerellesnumeriques.org>
Date:   Mon Jul 28 07:33:30 2025 +0700

    create logout

booking_travel_api/app/Http/Controllers/UserController.php
booking_travel_api/app/Http/Controllers/UserController.php.orig
booking_travel_api/routes/api.php
booking_travel_api/routes/api.php.orig

[33mcommit 209262a5a6c49a74f22b55e5c2ca2fe0c555736f[m
Author: kimchhik-chhuong <kimchhik.chhuong@student.passerellesnumeriques.org>
Date:   Sat Jul 26 17:52:34 2025 +0700

    dashbord api

booking_travel_api/app/Http/Controllers/UserController.php.orig

[33mcommit 6d7ed4f9ca04fa0ae41c8efba7211a9dd9dc5e51[m
Merge: 29086c4 fc20952
Author: kimchhik-chhuong <kimchhik.chhuong@student.passerellesnumeriques.org>
Date:   Fri Jul 25 15:14:46 2025 +0700

    payment

[33mcommit 29086c4b9d4c4fb4310e652a4cf666058afdac2b[m
Author: kimchhik-chhuong <kimchhik.chhuong@student.passerellesnumeriques.org>
Date:   Fri Jul 25 15:13:22 2025 +0700

    WIP: local changes before merging payment

booking_travel_api/routes/api.php
booking_travel_api/routes/api.php.orig

[33mcommit fc20952206861abbcad7d8111945338c59873443[m
Author: kimchhik-chhuong <kimchhik.chhuong@student.passerellesnumeriques.org>
Date:   Fri Jul 25 15:07:42 2025 +0700

    fix permission

booking_travel_api/app/Http/Controllers/UserController.php
booking_travel_api/app/Http/Controllers/UserController.php.orig
booking_travel_api/composer.json
booking_travel_api/config/permission.php
booking_travel_api/database/migrations/2025_07_25_005609_create_permission_tables.php
booking_travel_api/routes/api.php
booking_travel_api/routes/api.php.orig

[33mcommit a9885ec3a98a9678e926e5fd358570150ea31a07[m
Author: kimchhik-chhuong <kimchhik.chhuong@student.passerellesnumeriques.org>
Date:   Fri Jul 25 14:59:39 2025 +0700

    add image login[TRAVEL-38]

booking_travel_flutter/lib/screens/home_screen.dart
booking_travel_flutter/lib/screens/search_screen.dart
booking_travel_flutter/pubspec.yaml

[33mcommit be0c5c291ad3a4ca5f63c8a05175f7504c7bcf6b[m[33m ([m[1;31morigin/fix[m[33m)[m
Merge: e2df907 95cc663
Author: Mean Un <mean.un.personal@gmail.com>
Date:   Fri Jul 25 14:41:19 2025 +0700

    merge code

[33mcommit e2df90733ab389e1abb8b370e0bbcce8c0e79551[m
Author: Mean Un <mean.un.personal@gmail.com>
Date:   Fri Jul 25 14:28:03 2025 +0700

    user login and register in flutter and api

booking_travel_api/app/Http/Controllers/AuthController.php
booking_travel_api/app/Http/Kernel.php
booking_travel_api/app/Http/Middleware/Authenticate.php
booking_travel_api/app/Http/Middleware/CustomCors.php
booking_travel_api/app/Http/Middleware/NoCache.php
booking_travel_api/app/Http/Middleware/RedirectIfAuthenticated.php
booking_travel_api/app/Http/Middleware/RoleMiddleware.php
booking_travel_api/app/Http/Middleware/TrustProxies.php
booking_travel_api/app/Providers/RouteServiceProvider.php
booking_travel_api/config/cors.php
booking_travel_api/resources/views/auth/login.blade.php
booking_travel_api/resources/views/dashboard.blade.php
booking_travel_api/routes/web.php
booking_travel_flutter/devtools_options.yaml
booking_travel_flutter/lib/screens/login.dart
booking_travel_flutter/lib/screens/register.dart
booking_travel_flutter/lib/services/user_service.dart
