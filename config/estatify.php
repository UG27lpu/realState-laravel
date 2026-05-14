<?php

/**
 * Project-specific settings. Centralised here so feature flags, demo toggles
 * and tunable defaults stay out of the .env scattering. Each key is mirrored
 * to .env.example for the demo systems.
 */
return [
    'demo' => [
        'ai_descriptions'     => env('DEMO_AI_DESCRIPTIONS', true),
        'price_prediction'    => env('DEMO_PRICE_PREDICTION', true),
        'legal_verification'  => env('DEMO_LEGAL_VERIFICATION', true),
        'digital_signature'   => env('DEMO_DIGITAL_SIGNATURE', true),
    ],

    'maps' => [
        'google_maps_key' => env('VITE_GOOGLE_MAPS_KEY'),
    ],

    'media' => [
        'max_property_images' => 20,
        'max_image_size_kb'   => 4096,
        'max_video_size_kb'   => 51200,
    ],

    'currency' => [
        'symbol' => '₹',
        'code'   => 'INR',
    ],
];
