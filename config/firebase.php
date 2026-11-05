<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Project & Service Account Configuration
    |--------------------------------------------------------------------------
    |
    | File kredensial Service Account terletak di:
    | storage/firebase/firebase-credentials.json (Project: nuevauno-1)
    |
    */
    'project_id' => env('FIREBASE_PROJECT_ID', 'nuevauno-1'),

    'credentials' => env('FIREBASE_CREDENTIALS', storage_path('firebase/firebase-credentials.json')),

    'database_url' => env('FIREBASE_DATABASE_URL', 'https://nuevauno-1-default-rtdb.asia-southeast1.firebasedatabase.app'),

    /*
    |--------------------------------------------------------------------------
    | Firebase Web SDK Configuration (Google Sign-In / Client Auth)
    |--------------------------------------------------------------------------
    */
    'web' => [
        'api_key'             => env('FIREBASE_API_KEY', ''),
        'auth_domain'         => env('FIREBASE_AUTH_DOMAIN', env('FIREBASE_PROJECT_ID', 'nuevauno-1') . '.firebaseapp.com'),
        'project_id'          => env('FIREBASE_PROJECT_ID', 'nuevauno-1'),
        'storage_bucket'      => env('FIREBASE_STORAGE_BUCKET', env('FIREBASE_PROJECT_ID', 'nuevauno-1') . '.firebasestorage.app'),
        'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID', ''),
        'app_id'              => env('FIREBASE_APP_ID', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Firestore NoSQL Migration & Sync Settings
    |--------------------------------------------------------------------------
    */
    'firestore' => [
        'database'      => env('FIREBASE_FIRESTORE_DATABASE', '(default)'),
        'sync_enabled'  => (bool) env('FIREBASE_SYNC_ENABLED', false),
        'snapshot_path' => storage_path('firebase/firestore-snapshot.json'),
    ],
];
