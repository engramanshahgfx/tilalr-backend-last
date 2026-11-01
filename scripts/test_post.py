import requests
import json

url = "https://admin.tilalr.com/api/bookings/guest"
headers = {
    "Content-Type": "application/json",
    "Accept": "application/json"
}
payload = {
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "mobile": "0501234567",
    "travel_date": "2026-08-01",
    "room_type": "DoubleRoom",
    "package_id": "30",
    "package_code": "PKG-30",
    "notes": "",
    "payment_method": "credit_card",
    "booking_type": "destination",
    "guests": 1,
    "special_requests": "",
    "total_amount": 1800,
    "price": 1800
}

response = requests.post(url, headers=headers, json=payload)
print("STATUS CODE:", response.status_code)
print("RESPONSE BODY:")
print(json.dumps(response.json(), indent=2, ensure_ascii=False))
