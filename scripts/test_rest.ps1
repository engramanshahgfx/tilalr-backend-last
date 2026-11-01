$body = @{
    first_name = "John"
    last_name = "Doe"
    email = "john@example.com"
    mobile = "0501234567"
    travel_date = "2026-08-01"
    room_type = "DoubleRoom"
    package_id = "30"
    package_code = "PKG-30"
    notes = ""
    payment_method = "credit_card"
    booking_type = "destination"
    guests = 1
    special_requests = ""
    total_amount = 1800
    price = 1800
} | ConvertTo-Json

try {
    $res = Invoke-RestMethod -Uri "https://admin.tilalr.com/api/bookings/guest" -Method Post -Body $body -ContentType "application/json" -Headers @{ Accept = "application/json" }
    Write-Output "SUCCESS:"
    Write-Output ($res | ConvertTo-Json -Depth 5)
} catch {
    Write-Output "ERROR STATUS:"
    Write-Output $_.Exception.Response.StatusCode
    $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
    $responseBody = $reader.ReadToEnd()
    Write-Output "RESPONSE BODY:"
    Write-Output $responseBody
}
