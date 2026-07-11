<?php

/**
 * Arabic translations for every validation rule this application's forms
 * can trigger (public booking, salon dashboard, and super admin panel),
 * plus friendly Arabic names for all form fields. Anything not listed
 * falls back to English via app.fallback_locale.
 */
return [
    'required' => 'حقل :attribute مطلوب.',
    'string' => 'يجب أن يكون حقل :attribute نصًا.',
    'integer' => 'يجب أن يكون حقل :attribute رقمًا صحيحًا.',
    'numeric' => 'يجب أن يكون حقل :attribute رقمًا.',
    'email' => 'يجب أن يكون حقل :attribute بريدًا إلكترونيًا صالحًا.',
    'exists' => 'القيمة المحددة في حقل :attribute غير صالحة.',
    'unique' => 'قيمة حقل :attribute مستخدمة من قبل.',
    'date' => 'يجب أن يكون حقل :attribute تاريخًا صالحًا.',
    'date_format' => 'حقل :attribute لا يطابق الصيغة المطلوبة.',
    'after_or_equal' => 'يجب أن يكون حقل :attribute تاريخًا يساوي أو يلي :date.',
    'regex' => 'صيغة حقل :attribute غير صالحة.',
    'image' => 'يجب أن يكون حقل :attribute صورة.',
    'boolean' => 'يجب أن تكون قيمة حقل :attribute صحيحة أو خاطئة.',
    'alpha_dash' => 'يجب أن يحتوي حقل :attribute على أحرف وأرقام وشرطات فقط.',
    'not_in' => 'القيمة المحددة في حقل :attribute غير مسموح بها.',

    'between' => [
        'numeric' => 'يجب أن تكون قيمة حقل :attribute بين :min و :max.',
    ],

    'min' => [
        'string' => 'يجب ألا يقل حقل :attribute عن :min أحرف.',
        'numeric' => 'يجب ألا تقل قيمة حقل :attribute عن :min.',
    ],

    'max' => [
        'string' => 'يجب ألا يتجاوز حقل :attribute :max حرفًا.',
        'file' => 'يجب ألا يتجاوز حجم حقل :attribute :max كيلوبايت.',
        'numeric' => 'يجب ألا تتجاوز قيمة حقل :attribute :max.',
    ],

    'attributes' => [
        'name' => 'الاسم',
        'slug' => 'النطاق الفرعي',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'phone' => 'رقم الهاتف',
        'address' => 'العنوان',
        'description' => 'الوصف',
        'is_active' => 'الحالة',
        'service_id' => 'الخدمة',
        'employee_id' => 'الموظف',
        'date' => 'التاريخ',
        'time' => 'الوقت',
        'customer_name' => 'اسم الزبون',
        'customer_phone' => 'رقم الهاتف',
        'customer_email' => 'البريد الإلكتروني',
        'notes' => 'الملاحظات',
        'duration_minutes' => 'المدة',
        'price' => 'السعر',
        'specialties' => 'الاختصاصات',
        'bio' => 'النبذة التعريفية',
        'sort_order' => 'ترتيب العرض',
        'day_of_week' => 'يوم الأسبوع',
        'start_date' => 'تاريخ البدء',
        'end_date' => 'تاريخ الانتهاء',
        'owner_name' => 'اسم المالك',
        'owner_email' => 'بريد المالك الإلكتروني',
        'owner_password' => 'كلمة مرور المالك',
        'subscription_starts_at' => 'بداية الاشتراك',
        'subscription_ends_at' => 'نهاية الاشتراك',
        'logo' => 'الشعار',
        'instagram' => 'حساب إنستغرام',
        'brand_color' => 'لون العلامة التجارية',
        'reason' => 'السبب',
    ],
];
