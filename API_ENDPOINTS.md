# API ENDPOINTS REFERENCE

**Project:** Tilal Rimal Travel Platform  
**Base URL:** http://localhost:8000  
**API Prefix:** /api  
**Authentication:** Sanctum Bearer Tokens  
**Response Format:** JSON  

---

## AUTHENTICATION ENDPOINTS

### 1. Register New User

```http
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "966501234567",
  "password": "SecurePassword123",
  "password_confirmation": "SecurePassword123"
}
```

**Response (200):**
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "966501234567",
    "role": "user"
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

---

### 2. Login

```http
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "SecurePassword123"
}
```

**Response (200):**
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "966501234567",
    "role": "user"
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

**Error (401):**
```json
{
  "message": "Invalid credentials"
}
```

---

### 3. Send OTP to Phone

```http
POST /api/auth/send-otp
Content-Type: application/json

{
  "phone": "966501234567"
}
```

**Response (200):**
```json
{
  "message": "OTP sent successfully",
  "phone": "966501234567",
  "expires_in_seconds": 300
}
```

**Note:** OTP will be:
- **Dev mode (`OTP_MODE=fixed`):** Always 1234567
- **Dev mode (`OTP_MODE=random`):** Random 6-digit (logged to console/file)
- **Production (`OTP_MODE=sms`):** Sent via SMS (Taqnyat/Twilio)

---

### 4. Verify OTP & Login

```http
POST /api/auth/verify-otp
Content-Type: application/json

{
  "phone": "966501234567",
  "otp_code": "123456"
}
```

**Response (200):**
```json
{
  "message": "OTP verified successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "phone": "966501234567"
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "is_new_user": false
}
```

**Error (401):**
```json
{
  "message": "Invalid or expired OTP"
}
```

---

### 5. Reset Password via OTP

```http
POST /api/auth/reset-password
Content-Type: application/json

{
  "phone": "966501234567",
  "otp_code": "123456",
  "new_password": "NewPassword123",
  "new_password_confirmation": "NewPassword123"
}
```

**Response (200):**
```json
{
  "message": "Password reset successfully"
}
```

---

### 6. Check if Email Exists

```http
GET /api/users/exists?email=john@example.com
```

**Response (200):**
```json
{
  "exists": false,
  "email": "john@example.com"
}
```

---

## ISLAND DESTINATIONS ENDPOINTS

### 7. Get All Islands (Public)

```http
GET /api/island-destinations
GET /api/island-destinations?type=local
GET /api/island-destinations?type=international
GET /api/island-destinations?active=1
GET /api/island-destinations?price_min=100&price_max=5000
GET /api/island-destinations?search=maldives
GET /api/island-destinations?sort=price&order=asc
GET /api/island-destinations?page=1&per_page=20
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "type": "international",
      "title_en": "Maldives Paradise Island",
      "title_ar": "جزيرة المالديف الفردوس",
      "slug": "maldives-paradise",
      "location_en": "Maldives",
      "price": 2500.00,
      "rating": 4.8,
      "image": "international/1.webp",
      "highlights_en": ["Water Sports", "Spa", "Fine Dining"],
      "includes_en": ["Flights", "Hotel", "Meals"],
      "duration_en": "7 Days",
      "groupSize_en": "2-4 People",
      "active": 1,
      "created_at": "2025-12-25T10:00:00Z",
      "updated_at": "2026-02-18T15:00:00Z"
    },
    ...
  ],
  "pagination": {
    "total": 45,
    "per_page": 20,
    "current_page": 1,
    "last_page": 3
  }
}
```

---

### 8. Get Local Islands Only

```http
GET /api/island-destinations/local
GET /api/island-destinations/local?page=1&per_page=15
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 50,
      "type": "local",
      "title_en": "Farasan Islands",
      "title_ar": "جزر فرسان",
      "slug": "farasan-islands",
      "location_en": "Saudi Arabia",
      "price": 500.00,
      "rating": 4.5,
      ...
    }
  ],
  "pagination": { ... }
}
```

---

### 9. Get Single Island Details

```http
GET /api/island-destinations/{id}
GET /api/island-destinations/{slug}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "type": "international",
    "title_en": "Maldives Paradise Island",
    "title_ar": "جزيرة المالديف الفردوس",
    "title_zh": "马尔代夫天堂岛",
    "slug": "maldives-paradise",
    "location_en": "Maldives",
    "location_ar": "المالديف",
    "location_zh": "马尔代夫",
    "description_en": "Experience luxury at its finest...",
    "description_ar": "اختبر الفخامة بأفضل...",
    "price": 2500.00,
    "rating": 4.8,
    "highlights_en": ["Water Sports", "Spa & Wellness", "Fine Dining"],
    "highlights_ar": ["الرياضات المائية", "منتجع صحي"],
    "includes_en": ["International Flights", "5-Star Hotel", "All Meals"],
    "includes_ar": ["الرحلات الجوية الدولية", "فندق 5 نجوم"],
    "itinerary_en": "Day 1: Arrival and check-in...",
    "itinerary_ar": "اليوم الأول: الوصول والتسجيل...",
    "duration_en": "7 Days / 6 Nights",
    "duration_ar": "7 أيام / 6 ليالي",
    "groupSize_en": "2-4 People",
    "groupSize_ar": "2-4 أشخاص",
    "features_en": ["WiFi", "Air Conditioning", "Swimming Pool"],
    "image": "international/1.webp",
    "active": 1,
    "created_at": "2025-12-25T10:00:00Z",
    "updated_at": "2026-02-18T15:00:00Z"
  }
}
```

**Error (404):**
```json
{
  "success": false,
  "message": "Island not found"
}
```

---

### 10. Create Island (Admin Only)

```http
POST /api/island-destinations
Authorization: Bearer {token}
Content-Type: application/json

{
  "type": "international",
  "title_en": "New Paradise Island",
  "title_ar": "جزيرة الفردوس الجديدة",
  "slug": "new-paradise-island",
  "location_en": "Caribbean",
  "location_ar": "البحر الكاريبي",
  "price": 3500.00,
  "rating": 4.5,
  "description_en": "An amazing tropical island...",
  "description_ar": "جزيرة استوائية مذهلة...",
  "highlights_en": ["Beach Access", "Snorkeling"],
  "highlights_ar": ["الوصول للشاطئ", "الغوص"],
  "includes_en": ["Hotel", "Breakfast"],
  "includes_ar": ["الفندق", "الإفطار"],
  "itinerary_en": "Detailed itinerary here...",
  "duration_en": "5 Days",
  "groupSize_en": "4-8 People",
  "features_en": ["WiFi", "Pool"],
  "active": 1
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Island created successfully",
  "data": {
    "id": 100,
    "type": "international",
    "title_en": "New Paradise Island",
    ...
  }
}
```

**Error (403):**
```json
{
  "success": false,
  "message": "Unauthorized"
}
```

---

### 11. Update Island (Admin Only)

```http
PUT /api/island-destinations/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "title_en": "Updated Title",
  "price": 3000.00,
  "rating": 4.7,
  "active": 1
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Island updated successfully",
  "data": { ... }
}
```

---

### 12. Delete Island (Admin Only)

```http
DELETE /api/island-destinations/{id}
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Island deleted successfully"
}
```

---

## BOOKING ENDPOINTS

### 13. Create Booking

```http
POST /api/bookings
Authorization: Bearer {token}
Content-Type: application/json

{
  "island_destination_id": 1,
  "check_in_date": "2026-03-15",
  "check_out_date": "2026-03-22",
  "number_of_guests": 2,
  "special_requests": "Honeymoon suite please",
  "total_price": 2500.00
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Booking created successfully",
  "data": {
    "id": 1001,
    "user_id": 5,
    "island_destination_id": 1,
    "booking_date": "2026-02-18",
    "check_in_date": "2026-03-15",
    "check_out_date": "2026-03-22",
    "number_of_guests": 2,
    "total_price": 2500.00,
    "status": "pending",
    "special_requests": "Honeymoon suite please",
    "created_at": "2026-02-18T15:00:00Z"
  }
}
```

---

### 14. Get All Bookings (Authenticated User)

```http
GET /api/bookings
Authorization: Bearer {token}
GET /api/bookings?status=confirmed
GET /api/bookings?status=pending
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1001,
      "island_destination_id": 1,
      "island_title": "Maldives Paradise Island",
      "status": "confirmed",
      "total_price": 2500.00,
      "check_in_date": "2026-03-15",
      "created_at": "2026-02-18T15:00:00Z"
    }
  ]
}
```

---

## TRIP & RESERVATION ENDPOINTS

### 15. Get All Trips

```http
GET /api/trips
GET /api/trips?destination=maldives
GET /api/trips?page=1&per_page=10
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title_en": "Caribbean Cruise",
      "title_ar": "رحلة البحر الكاريبي",
      "slug": "caribbean-cruise",
      "destination": "Caribbean",
      "price": 2800.00,
      "duration": "8 Days",
      "start_date": "2026-03-20",
      "end_date": "2026-03-28",
      "max_participants": 30,
      "current_participants": 12,
      "image": "trips/caribbean.webp"
    }
  ],
  "pagination": { ... }
}
```

---

### 16. Get Trip Blocked Dates

```http
GET /api/trips/{slug}/blocked-dates
```

**Response (200):**
```json
{
  "success": true,
  "blocked_dates": [
    "2026-03-15",
    "2026-03-16",
    "2026-03-17",
    "2026-04-10"
  ]
}
```

---

### 17. Create Reservation

```http
POST /api/reservations
Authorization: Bearer {token}
Content-Type: application/json

{
  "trip_id": 1,
  "departure_date": "2026-03-20",
  "number_of_guests": 3,
  "guest_names": ["John Doe", "Jane Doe", "Bob Smith"],
  "contact_phone": "966501234567",
  "contact_email": "john@example.com"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Reservation created successfully",
  "data": {
    "id": 50,
    "trip_id": 1,
    "user_id": 5,
    "departure_date": "2026-03-20",
    "number_of_guests": 3,
    "total_price": 8400.00,
    "status": "pending",
    "created_at": "2026-02-18T15:00:00Z"
  }
}
```

---

### 18. Get My Reservations

```http
GET /api/reservations
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 50,
      "trip_id": 1,
      "trip_title": "Caribbean Cruise",
      "departure_date": "2026-03-20",
      "number_of_guests": 3,
      "total_price": 8400.00,
      "status": "confirmed"
    }
  ]
}
```

---

## PAYMENT ENDPOINTS

### 19. Create Payment (Stripe Checkout)

```http
POST /api/payments
Authorization: Bearer {token}
Content-Type: application/json

{
  "item_type": "booking",         // 'booking' or 'reservation'
  "item_id": 1001,
  "amount": 2500.00,
  "currency": "USD"
}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "payment_id": 100,
    "client_secret": "pi_1234567890_secret_1234567890",
    "publishable_key": "pk_live_XXXXXXXXXX",
    "amount": 2500,
    "currency": "usd"
  }
}
```

---

### 20. Confirm Payment

```http
POST /api/payments/{id}/confirm
Authorization: Bearer {token}
Content-Type: application/json

{
  "payment_intent_id": "pi_1234567890",
  "stripe_charge_id": "ch_1234567890"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Payment confirmed successfully",
  "data": {
    "id": 100,
    "status": "paid",
    "amount": 2500.00,
    "receipt_url": "https://receipts.stripe.com/..."
  }
}
```

---

### 21. Check Payment Status

```http
GET /api/payments/{id}
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 100,
    "amount": 2500.00,
    "currency": "USD",
    "status": "paid",
    "payment_method": "stripe",
    "booking_id": 1001,
    "receipt_url": "https://...",
    "created_at": "2026-02-18T14:30:00Z"
  }
}
```

---

## CONTENT ENDPOINTS

### 22. Get All Pages

```http
GET /api/pages
GET /api/pages?language=ar
GET /api/pages?published=1
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title_en": "About Us",
      "title_ar": "من نحن",
      "slug": "about-us",
      "content_en": "Lorem ipsum...",
      "content_ar": "لوريم إيبسوم...",
      "published": 1
    }
  ]
}
```

---

### 23. Get Single Page

```http
GET /api/pages/{slug}
GET /api/pages/about-us?language=ar
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title_en": "About Us",
    "slug": "about-us",
    "content_en": "Full page content...",
    "featured_image": "pages/about.jpg"
  }
}
```

---

### 24. Get All Services

```http
GET /api/services
GET /api/services?language=ar
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title_en": "Flight Booking",
      "title_ar": "حجز الرحلات",
      "slug": "flight-booking",
      "description_en": "Book international flights...",
      "image": "services/flights.jpg",
      "active": 1
    }
  ]
}
```

---

### 25. Get Testimonials

```http
GET /api/testimonials
GET /api/testimonials?published=1
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "author_en": "John Doe",
      "author_ar": "جون دو",
      "rating": 5,
      "comment_en": "Amazing experience!",
      "comment_ar": "تجربة رائعة!",
      "image": "testimonials/john.jpg"
    }
  ]
}
```

---

### 26. Get Settings

```http
GET /api/settings
GET /api/settings/site_name
```

**Response (200):**
```json
{
  "success": true,
  "data": {
    "site_name": "Tilal Rimal",
    "theme_color": "#1e40af",
    "phone":  966"اتصل بنا506073054966+",
    "email": "info@tilalr.com",
    "whatsapp_number": "+966501234567"
  }
}
```

---

## INTERNATIONAL TRAVEL ENDPOINTS

### 27. Get International Flights

```http
GET /api/international/flights
GET /api/international/flights?airline=Emirates
GET /api/international/flights?departure_city=Dubai&arrival_city=London
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "airline": "Emirates",
      "flight_number": "EK001",
      "departure_city_en": "Dubai",
      "arrival_city_en": "London",
      "departure_time": "14:30",
      "arrival_time": "19:45",
      "duration_minutes": 835,
      "price": 450.00,
      "available_seats": 120,
      "aircraft_type": "Boeing 777"
    }
  ]
}
```

---

### 28. Get International Hotels

```http
GET /api/international/hotels
GET /api/international/hotels?city=Dubai
GET /api/international/hotels?rating=5
GET /api/international/hotels?price_max=500
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name_en": "Luxe Hotel Dubai",
      "name_ar": "فندق لوكس دبي",
      "city_en": "Dubai",
      "rating": 5,
      "price_per_night": 450.00,
      "rooms_available": 25,
      "room_types": ["Single", "Double", "Suite"],
      "amenities": ["WiFi", "Pool", "Gym", "Restaurant"],
      "image": "hotels/luxe-dubai.jpg"
    }
  ]
}
```

---

### 29. Get International Packages

```http
GET /api/international/packages
GET /api/international/packages?destination=maldives
GET /api/international/packages?duration_days=7
```

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title_en": "Maldives 7-Day Package",
      "title_ar": "باقة المالديف 7 أيام",
      "destination_en": "Maldives",
      "price": 2500.00,
      "duration_days": 7,
      "included_services": ["Flights", "5-Star Hotel", "All Meals", "Tours"],
      "start_date": "2026-03-15",
      "availability": "Available",
      "image": "packages/maldives-7day.jpg"
    }
  ]
}
```

---

## SYSTEM ENDPOINTS

### 30. Health Check

```http
GET /api/health
```

**Response (200):**
```json
{
  "status": "ok",
  "message": "API is running",
  "timestamp": "2026-02-18T15:00:00Z"
}
```

---

### 31. Database Health Check

```http
GET /api/health/db
```

**Response (200):**
```json
{
  "status": "ok",
  "database": "connected",
  "response_time_ms": 5
}
```

---

### 32. SMS Status (Dev Only)

```http
GET /api/sms/status
```

**Response (200):**
```json
{
  "provider": "taqnyat",
  "status": "active",
  "balance": 500,
  "mode": "production"
}
```

---

### 33. Test SMS Send (Dev Only)

```http
POST /api/sms/test
Content-Type: application/json

{
  "phone": "966501234567",
  "message": "Test message from Tilal Rimal API"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "SMS sent successfully",
  "phone": "966501234567",
  "provider": "taqnyat",
  "message_id": "123456789"
}
```

---

## ERROR RESPONSES

### Common Error Codes

#### 400 Bad Request
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

#### 401 Unauthorized
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

#### 403 Forbidden
```json
{
  "success": false,
  "message": "Unauthorized - insufficient permissions"
}
```

#### 404 Not Found
```json
{
  "success": false,
  "message": "Resource not found"
}
```

#### 422 Unprocessable Entity
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "rating": ["Rating must be between 0 and 5"]
  }
}
```

#### 429 Too Many Requests
```json
{
  "success": false,
  "message": "Rate limit exceeded. Please try again later."
}
```

#### 500 Server Error
```json
{
  "success": false,
  "message": "Internal server error"
}
```

---

## AUTHENTICATION HEADERS

### Required for Protected Endpoints

```http
Authorization: Bearer {api_token}
```

**Example:**
```http
GET /api/bookings
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

---

## QUERY PARAMETERS

### Pagination

```
GET /api/island-destinations?page=2&per_page=20
```

### Filtering

```
GET /api/bookings?status=confirmed&price_min=100&price_max=5000
```

### Sorting

```
GET /api/bookings?sort=created_at&order=desc
GET /api/bookings?sort=price&order=asc
```

### Language Selection

```
GET /api/pages/about-us?language=ar
GET /api/pages/about-us?language=zh
```

### Searching

```
GET /api/island-destinations?search=maldives
GET /api/testimonials?search=amazing
```

---

## TESTING API WITH CURL

### Register
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "966501234567",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Get Islands
```bash
curl -X GET "http://localhost:8000/api/island-destinations?page=1&per_page=10"
```

### Authenticated Request
```bash
curl -X GET "http://localhost:8000/api/bookings" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Create Payment
```bash
curl -X POST http://localhost:8000/api/payments \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "item_type": "booking",
    "item_id": 1001,
    "amount": 2500.00,
    "currency": "USD"
  }'
```

---

## TESTING WITH POSTMAN

1. Import the Postman collection (if provided)
2. Set up environment variables:
   - `{{base_url}}` = http://localhost:8000
   - `{{token}}` = Your Bearer Token
   - `{{user_id}}` = Your User ID

3. Use pre-request scripts to automatically set token:
   ```javascript
   // After login, store token:
   pm.environment.set("token", pm.response.json().token);
   ```

---

**Document Version:** 1.0  
**Last Updated:** February 18, 2026  
**Status:** Production Ready
