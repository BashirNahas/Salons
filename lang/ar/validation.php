<?php

/**
 * Arabic translations for the validation rules actually used on the
 * customer-facing booking form (see Http/Requests/StoreBookingRequest).
 * Not a full port of Laravel's validation language file — only the rules
 * this app's public forms trigger, so every message a customer can see is
 * translated; anything else falls back to English via app.fallback_locale.
 */
return [
    'required' => 'حقل :attribute مطلوب.',
    'string' => 'يجب أن يكون حقل :attribute نصًا.',
    'integer' => 'يجب أن يكون حقل :attribute رقمًا صحيحًا.',
    'email' => 'يجب أن يكون حقل :attribute بريدًا إلكترونيًا صالحًا.',
    'exists' => 'القيمة المحددة لحقل :attribute غير صالحة.',
    'date' => 'يجب أن يكون حقل :attribute تاريخًا صالحًا.',
    'date_format' => 'لا يتطابق حقل :attribute مع الصيغة المطلوبة.',
    'after_or_equal' => 'يجب أن يكون حقل :attribute تاريخًا بعد أو يساوي :date.',
    'regex' => 'صيغة حقل :attribute غير صالحة.',
    'image' => 'يجب أن يكون حقل :attribute صورة.',

    'max' => [
        'string' => 'يجب ألا يتجاوز حقل :attribute :max حرفًا.',
        'file' => 'يجب ألا يتجاوز حجم حقل :attribute :max كيلوبايت.',
        'numeric' => 'يجب ألا يتجاوز حقل :attribute :max.',
    ],

    'attributes' => [
        'service_id' => 'الخدمة',
        'employee_id' => 'الموظف',
        'date' => 'التاريخ',
        'time' => 'الوقت',
        'customer_name' => 'الاسم',
        'customer_phone' => 'رقم الهاتف',
        'customer_email' => 'البريد الإلكتروني',
        'notes' => 'ملاحظات',
    ],
];
