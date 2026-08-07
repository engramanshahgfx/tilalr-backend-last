<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TourismOffer;

class TourismOfferSeeder extends Seeder
{
    public function run(): void
    {
        $offers = [
            [
                // Basic Info - English & Arabic
                'title_en' => 'Luxury Beach Resort',
                'title_ar' => 'منتجع شاطئ فاخر',
                'description_en' => 'Experience luxury like never before with stunning ocean views',
                'description_ar' => 'اختبر الفخامة كما لم يسبق لك مع إطلالات خلابة على المحيط',
                'long_description_en' => 'This luxury beach resort offers world-class amenities, private beaches, and breathtaking views.',
                'long_description_ar' => 'يقدم هذا المنتجع الفاخر مرافق عالمية المستوى وشواطئ خاصة ومناظر خلابة.',

                // Slug (unique identifier - usually not translated)
                'slug' => 'luxury-beach-resort',

                // Image
                'image' => 'offers/luxury-beach.jpg',

                // Pricing
                'price' => 2500,
                'original_price' => 3200,
                'discount' => 20,
                'rating' => 4.8,

                // Duration - English & Arabic
                'duration_en' => '5 Days',
                'duration_ar' => '٥ أيام',

                // Location - English & Arabic
                'location_en' => 'Maldives',
                'location_ar' => 'المالديف',

                // Group Size - English & Arabic
                'group_size_en' => '2-4 People',
                'group_size_ar' => '٢-٤ أشخاص',

                // Features - English & Arabic
                'features_en' => json_encode(['Private Beach', 'Spa & Wellness', 'Fine Dining', 'Water Sports']),
                'features_ar' => json_encode(['شاطئ خاص', 'سبا وعافية', 'تناول طعام فاخر', 'رياضات مائية']),

                // Includes - English & Arabic
                'includes_en' => json_encode(['Airport Transfer', 'Daily Breakfast', 'Wifi', 'Pool Access']),
                'includes_ar' => json_encode(['النقل من المطار', 'وجبة إفطار يومية', 'واي فاي', 'الوصول إلى المسبح']),

                // Not Includes - English & Arabic
                'not_includes_en' => json_encode(['Airline Tickets', 'Travel Insurance', 'Personal Expenses']),
                'not_includes_ar' => json_encode(['تذاكر الطيران', 'تأمين السفر', 'النفقات الشخصية']),

                // Itinerary - English & Arabic
                'itinerary_en' => json_encode([
                    ['day' => 1, 'title' => 'Arrival & Welcome', 'description' => 'Arrive at the resort and receive a warm welcome'],
                    ['day' => 2, 'title' => 'Beach Day', 'description' => 'Relax and enjoy the private beach'],
                    ['day' => 3, 'title' => 'Spa & Wellness', 'description' => 'Indulge in luxurious spa treatments'],
                    ['day' => 4, 'title' => 'Water Sports', 'description' => 'Enjoy various water sports activities'],
                    ['day' => 5, 'title' => 'Departure', 'description' => 'Check out and departure'],
                ]),
                'itinerary_ar' => json_encode([
                    ['day' => 1, 'title' => 'الوصول والترحيب', 'description' => 'الوصول إلى المنتجع وتلقي ترحيب حار'],
                    ['day' => 2, 'title' => 'يوم الشاطئ', 'description' => 'الاسترخاء والاستمتاع بالشاطئ الخاص'],
                    ['day' => 3, 'title' => 'السبا والعافية', 'description' => 'الاستمتاع بعلاجات السبا الفاخرة'],
                    ['day' => 4, 'title' => 'الرياضات المائية', 'description' => 'الاستمتاع بأنشطة الرياضات المائية المتنوعة'],
                    ['day' => 5, 'title' => 'المغادرة', 'description' => 'تسجيل المغادرة'],
                ]),

                // Basic Info (all fields with English & Arabic where applicable)
                'basic_info' => json_encode([
                    'trip_code' => 'T001',
                    'days_num' => '5',
                    'destination_name_en' => 'Maldives',
                    'destination_name_ar' => 'المالديف',
                    'available_to' => '2025-12-31',
                    'double_room_en' => 'Double Room',
                    'double_room_ar' => 'غرفة مزدوجة',
                    'double_room_price' => '2500',
                    'single_room_en' => 'Single Room',
                    'single_room_ar' => 'غرفة فردية',
                    'single_room_price' => '1800',
                    'trip_type_en' => 'International',
                    'trip_type_ar' => 'دولي',
                    'transport_en' => 'Private Car',
                    'transport_ar' => 'سيارة خاصة',
                    'meal_plan_en' => 'Full Board',
                    'meal_plan_ar' => 'إقامة كاملة',
                ]),

                // Contact Info (already bilingual)
                'contact_info' => json_encode([
                    'address_en' => 'Al Rabwa, Jeddah',
                    'address_ar' => 'الربوة، جدة',
                    'phone' => '0547305060',
                    'whatsapp' => '966547305060',
                    'email' => 'info@tilalr.com',
                ]),

                // Payment Methods (add bilingual descriptions)
                'payment_methods' => json_encode([
                    [
                        'name_en' => 'Alinma Bank',
                        'name_ar' => 'بنك الإنماء',
                        'account_no' => '68205990876000',
                        'iban' => 'SA3705000068205990876000',
                    ],
                ]),

                // Type (with bilingual)
                'type' => 'international',

                // Status fields
                'active' => true,
                'popular' => true,
                'limited' => false,
                'city' => 'riyadh',
            ],
            [
                // Second Offer - Mountain Adventure
                'title_en' => 'Mountain Adventure',
                'title_ar' => 'مغامرة جبلية',
                'description_en' => 'Explore the breathtaking mountains with guided tours',
                'description_ar' => 'استكشف الجبال الخلابة مع جولات إرشادية',
                'long_description_en' => 'Experience the thrill of mountain adventure with expert guides.',
                'long_description_ar' => 'جرب متعة المغامرة الجبلية مع مرشدين خبراء.',
                'slug' => 'mountain-adventure',
                'image' => 'offers/mountain-adventure.jpg',
                'price' => 1800,
                'original_price' => 2200,
                'discount' => 15,
                'rating' => 4.6,
                'duration_en' => '4 Days',
                'duration_ar' => '٤ أيام',
                'location_en' => 'Swiss Alps',
                'location_ar' => 'جبال الألب السويسرية',
                'group_size_en' => '2-6 People',
                'group_size_ar' => '٢-٦ أشخاص',
                'features_en' => json_encode(['Guided Tours', 'Scenic Trails', 'Mountain Views', 'Camping']),
                'features_ar' => json_encode(['جولات إرشادية', 'مسارات خلابة', 'إطلالات جبلية', 'تخييم']),
                'includes_en' => json_encode(['Professional Guide', 'Equipment', 'Lunch', 'Water']),
                'includes_ar' => json_encode(['مرشد محترف', 'معدات', 'وجبة غداء', 'ماء']),
                'not_includes_en' => json_encode(['Airline Tickets', 'Hotel', 'Personal Expenses']),
                'not_includes_ar' => json_encode(['تذاكر الطيران', 'فندق', 'النفقات الشخصية']),
                'itinerary_en' => json_encode([
                    ['day' => 1, 'title' => 'Arrival & Briefing', 'description' => 'Arrive and receive safety briefing'],
                    ['day' => 2, 'title' => 'Mountain Trail', 'description' => 'Hike through scenic mountain trails'],
                    ['day' => 3, 'title' => 'Summit View', 'description' => 'Reach the summit and enjoy panoramic views'],
                    ['day' => 4, 'title' => 'Return & Departure', 'description' => 'Return and depart'],
                ]),
                'itinerary_ar' => json_encode([
                    ['day' => 1, 'title' => 'الوصول والإحاطة', 'description' => 'الوصول وتلقي إحاطة السلامة'],
                    ['day' => 2, 'title' => 'مسار الجبل', 'description' => 'المشي عبر مسارات الجبل الخلابة'],
                    ['day' => 3, 'title' => 'إطلالة القمة', 'description' => 'الوصول إلى القمة والاستمتاع بإطلالات بانورامية'],
                    ['day' => 4, 'title' => 'العودة والمغادرة', 'description' => 'العودة والمغادرة'],
                ]),
                'basic_info' => json_encode([
                    'trip_code' => 'T002',
                    'days_num' => '4',
                    'destination_name_en' => 'Swiss Alps',
                    'destination_name_ar' => 'جبال الألب السويسرية',
                    'available_to' => '2025-12-31',
                    'double_room_en' => 'Double Room',
                    'double_room_ar' => 'غرفة مزدوجة',
                    'double_room_price' => '1800',
                    'single_room_en' => 'Single Room',
                    'single_room_ar' => 'غرفة فردية',
                    'single_room_price' => '1300',
                    'trip_type_en' => 'International',
                    'trip_type_ar' => 'دولي',
                    'transport_en' => 'Mini Bus',
                    'transport_ar' => 'حافلة صغيرة',
                    'meal_plan_en' => 'Half Board',
                    'meal_plan_ar' => 'نصف إقامة',
                ]),
                'contact_info' => json_encode([
                    'address_en' => 'King Fahd Road, Jeddah',
                    'address_ar' => 'طريق الملك فهد، جدة',
                    'phone' => '0547305060',
                    'whatsapp' => '966547305060',
                    'email' => 'info@tilalr.com',
                ]),
                'payment_methods' => json_encode([
                 
                    [
                        'name_en' => 'Apple Pay',
                        'name_ar' => 'آبل باي',
                        'account_no' => '3333',
                        'iban' => '3333333',
                    ],
                ]),
                'type' => 'international',
                'active' => true,
                'popular' => true,
                'limited' => true,
                'city' => 'riyadh',
            ],
            [
                // Third Offer - Cultural City Tour
                'title_en' => 'Cultural City Tour',
                'title_ar' => 'جولة مدينة ثقافية',
                'description_en' => 'Immerse yourself in rich history and culture',
                'description_ar' => 'انغمس في التاريخ والثقافة الغنية',
                'long_description_en' => 'Discover the rich cultural heritage of historic cities.',
                'long_description_ar' => 'اكتشف التراث الثقافي الغني للمدن التاريخية.',
                'slug' => 'cultural-city-tour',
                'image' => 'offers/cultural-tour.jpg',
                'price' => 1200,
                'original_price' => 1500,
                'discount' => null,
                'rating' => 4.7,
                'duration_en' => '3 Days',
                'duration_ar' => '٣ أيام',
                'location_en' => 'Istanbul',
                'location_ar' => 'اسطنبول',
                'group_size_en' => '2-8 People',
                'group_size_ar' => '٢-٨ أشخاص',
                'features_en' => json_encode(['Historical Sites', 'Museums', 'Local Cuisine', 'Shopping']),
                'features_ar' => json_encode(['مواقع تاريخية', 'متاحف', 'مأكولات محلية', 'تسوق']),
                'includes_en' => json_encode(['Professional Guide', 'Entrance Fees', 'Lunch', 'Water']),
                'includes_ar' => json_encode(['مرشد محترف', 'رسوم الدخول', 'وجبة غداء', 'ماء']),
                'not_includes_en' => json_encode(['Airline Tickets', 'Hotel', 'Personal Expenses']),
                'not_includes_ar' => json_encode(['تذاكر الطيران', 'فندق', 'النفقات الشخصية']),
                'itinerary_en' => json_encode([
                    ['day' => 1, 'title' => 'Old City Tour', 'description' => 'Explore historic landmarks of the old city'],
                    ['day' => 2, 'title' => 'Museum Day', 'description' => 'Visit world-class museums and galleries'],
                    ['day' => 3, 'title' => 'Cultural Experience', 'description' => 'Experience local traditions and cuisine'],
                ]),
                'itinerary_ar' => json_encode([
                    ['day' => 1, 'title' => 'جولة المدينة القديمة', 'description' => 'استكشف المعالم التاريخية للمدينة القديمة'],
                    ['day' => 2, 'title' => 'يوم المتاحف', 'description' => 'زيارة المتاحف والمعارض العالمية'],
                    ['day' => 3, 'title' => 'تجربة ثقافية', 'description' => 'اختبار التقاليد المحلية والمأكولات'],
                ]),
                'basic_info' => json_encode([
                    'trip_code' => 'T003',
                    'days_num' => '3',
                    'destination_name_en' => 'Istanbul',
                    'destination_name_ar' => 'اسطنبول',
                    'available_to' => '2025-12-31',
                    'double_room_en' => 'Double Room',
                    'double_room_ar' => 'غرفة مزدوجة',
                    'double_room_price' => '1200',
                    'single_room_en' => 'Single Room',
                    'single_room_ar' => 'غرفة فردية',
                    'single_room_price' => '900',
                    'trip_type_en' => 'International',
                    'trip_type_ar' => 'دولي',
                    'transport_en' => 'Tour Bus',
                    'transport_ar' => 'حافلة سياحية',
                    'meal_plan_en' => 'Breakfast Only',
                    'meal_plan_ar' => 'إفطار فقط',
                ]),
                'contact_info' => json_encode([
                    'address_en' => 'Al Rabwa, Jeddah',
                    'address_ar' => 'الربوة، جدة',
                    'phone' => '0547305060',
                    'whatsapp' => '966547305060',
                    'email' => 'info@tilalr.com',
                ]),
                'payment_methods' => json_encode([
                 
                    [
                        'name_en' => 'Mada Card',
                        'name_ar' => 'بطاقة مدى',
                        'account_no' => '4444',
                        'iban' => '4444444',
                    ],
                ]),
                'type' => 'international',
                'active' => true,
                'popular' => false,
                'limited' => false,
                'city' => 'riyadh',
            ],
            [
                // Fourth Offer - Domestic Tour (example)
                'title_en' => 'AlUla Heritage Tour',
                'title_ar' => 'جولة العلا التراثية',
                'description_en' => 'Discover the ancient wonders of AlUla',
                'description_ar' => 'اكتشف عجائب العلا القديمة',
                'long_description_en' => 'Explore the stunning archaeological sites and natural beauty of AlUla.',
                'long_description_ar' => 'استكشف المواقع الأثرية المذهلة والجمال الطبيعي للعلا.',
                'slug' => 'alula-heritage-tour',
                'image' => 'offers/alula-tour.jpg',
                'price' => 800,
                'original_price' => 1000,
                'discount' => 20,
                'rating' => 4.9,
                'duration_en' => '2 Days',
                'duration_ar' => 'يومان',
                'location_en' => 'AlUla, Saudi Arabia',
                'location_ar' => 'العلا، المملكة العربية السعودية',
                'group_size_en' => '2-10 People',
                'group_size_ar' => '٢-١٠ أشخاص',
                'features_en' => json_encode(['Archaeological Sites', 'Desert Landscapes', 'Historical Tours', 'Photography']),
                'features_ar' => json_encode(['مواقع أثرية', 'مناظر صحراوية', 'جولات تاريخية', 'تصوير فوتوغرافي']),
                'includes_en' => json_encode(['Guide', 'Transport', 'Lunch', 'Water']),
                'includes_ar' => json_encode(['مرشد', 'نقل', 'وجبة غداء', 'ماء']),
                'not_includes_en' => json_encode(['Hotel', 'Personal Expenses']),
                'not_includes_ar' => json_encode(['فندق', 'النفقات الشخصية']),
                'itinerary_en' => json_encode([
                    ['day' => 1, 'title' => 'Arrival & Site Tour', 'description' => 'Arrive and visit archaeological sites'],
                    ['day' => 2, 'title' => 'Desert Experience', 'description' => 'Experience desert landscapes and depart'],
                ]),
                'itinerary_ar' => json_encode([
                    ['day' => 1, 'title' => 'الوصول وجولة المواقع', 'description' => 'الوصول وزيارة المواقع الأثرية'],
                    ['day' => 2, 'title' => 'تجربة الصحراء', 'description' => 'تجربة المناظر الصحراوية والمغادرة'],
                ]),
                'basic_info' => json_encode([
                    'trip_code' => 'T004',
                    'days_num' => '2',
                    'destination_name_en' => 'AlUla',
                    'destination_name_ar' => 'العلا',
                    'available_to' => '2025-12-31',
                    'double_room_en' => 'Double Room',
                    'double_room_ar' => 'غرفة مزدوجة',
                    'double_room_price' => '800',
                    'single_room_en' => 'Single Room',
                    'single_room_ar' => 'غرفة فردية',
                    'single_room_price' => '600',
                    'trip_type_en' => 'Domestic',
                    'trip_type_ar' => 'محلي',
                    'transport_en' => '4x4 Vehicle',
                    'transport_ar' => 'سيارة دفع رباعي',
                    'meal_plan_en' => 'Lunch Only',
                    'meal_plan_ar' => 'غداء فقط',
                ]),
                'contact_info' => json_encode([
                    'address_en' => 'Al Rabwa, Jeddah',
                    'address_ar' => 'الربوة، جدة',
                    'phone' => '0547305060',
                    'whatsapp' => '966547305060',
                    'email' => 'info@tilalr.com',
                ]),
                'payment_methods' => json_encode([
                  
                ]),
                'type' => 'domestic',
                'active' => true,
                'popular' => true,
                'limited' => false,
                'city' => 'alula',
            ],
        ];

        foreach ($offers as $offer) {
            TourismOffer::updateOrCreate(
                ['slug' => $offer['slug']],
                $offer
            );
        }
    }
}
