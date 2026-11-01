const payload = {
    first_name: "John",
    last_name: "Doe",
    email: "john@example.com",
    mobile: "0501234567",
    travel_date: "2026-08-01",
    room_type: "DoubleRoom",
    package_id: "30",
    package_code: "PKG-30",
    notes: "",
    payment_method: "credit_card",
    booking_type: "destination",
    guests: 1,
    special_requests: "",
    total_amount: 1800,
    price: 1800
};

fetch("http://127.0.0.1:8000/api/bookings/guest", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "Accept": "application/json"
    },
    body: JSON.stringify(payload)
})
.then(async res => {
    console.log("STATUS CODE:", res.status);
    try {
        const json = await res.json();
        console.log("RESPONSE BODY:", JSON.stringify(json, null, 2));
    } catch (e) {
        console.log("RESPONSE TEXT:", await res.text());
    }
})
.catch(err => {
    console.error("ERROR:", err);
});
