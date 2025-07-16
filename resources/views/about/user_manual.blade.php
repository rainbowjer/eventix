@extends('layouts.app')
@section('content')
<div class="bg-gradient p-5 mb-4 rounded-3" style="background: linear-gradient(90deg, #d68de1 0%, #883aff 100%); min-height: 180px; display: flex; align-items: center; justify-content: center;">
    <div class="text-center w-100" style="color: #111;">
        <h1 class="display-5 fw-bold mb-0" style="color: #111;">User Manual</h1>
        <p class="lead" style="color: #111;">How to use EventiX as a User or Organizer</p>
    </div>
</div>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4" style="max-height: 70vh; overflow-y: auto; background: #f8f9fa;">
                    <pre style="white-space: pre-wrap; background: none; border: none; font-size: 1rem; color: #222;">
{{
    "EventiX User Manual\n\n".
    "1. Introduction\n".
    "EventiX is an event management and ticketing platform for users and organizers. This manual provides step-by-step instructions for both roles.\n\n".
    "2. For Users\n".
    "2.1. Account Registration and Login\n- Visit the EventiX homepage.\n- Click 'Register' to create a new account. Fill in your details and submit.\n- If you already have an account, click 'Login' and enter your credentials.\n- Verify your email if prompted.\n\n".
    "2.2. Browsing and Searching Events\n- After logging in, browse the homepage or use the search bar to find events.\n- Filter events by category, date, or location as needed.\n- Click on an event to view details, including date, time, venue, and available seats.\n\n".
    "2.3. Booking Tickets\n- On the event page, select your desired seat(s) and ticket quantity.\n- Click 'Book Now' or 'Proceed to Checkout.'\n- Review your order and confirm payment method.\n- Complete the payment process. You will receive a confirmation and e-ticket.\n\n".
    "2.4. Viewing and Managing Bookings\n- Go to your profile or 'My Bookings' section.\n- View all your current and past bookings.\n- Download or view your e-tickets.\n\n".
    "2.5. Reselling Tickets\n- In 'My Bookings,' select a ticket you wish to resell.\n- Click 'Resell Ticket' and set your resell price (if allowed).\n- Submit the resell request. You will be notified when your ticket is resold or if further action is needed.\n\n".
    "2.6. Managing Profile and Favorites\n- Access your profile to update personal information or change your password.\n- Add events to your favorites for quick access.\n- View your recently viewed events.\n\n".
    "2.7. Notifications\n- Check the notifications section for updates on bookings, resell status, and event reminders.\n\n".
    "3. For Organizers\n".
    "3.1. Organizer Login\n- Organizers log in using their assigned credentials.\n- If you are an organizer and do not have an account, contact the EventiX admin.\n\n".
    "3.2. Creating and Managing Events\n- After logging in, go to the 'Organizer Dashboard.'\n- Click 'Create Event' and fill in event details (name, date, time, venue, ticket types, etc.).\n- Save the event. You can edit or update event details anytime from the dashboard.\n\n".
    "3.3. Viewing Ticket Sales and Reports\n- In the dashboard, view ticket sales statistics and export reports as needed.\n- Monitor sales trends and attendee information.\n\n".
    "3.4. Managing Resell Requests\n- Review resell requests from users in the 'Resell Management' section.\n- Approve or reject requests and add notes if necessary.\n- Users will be notified of your decision.\n\n".
    "3.5. Viewing Event Reviews\n- Access event reviews submitted by attendees.\n- Use feedback to improve future events.\n\n".
    "4. Support\n- For help, contact EventiX support at support@eventix.com or visit the Help section on the website.\n- For organizer-specific issues, reach out to your EventiX account manager.\n\n".
    "Thank you for using EventiX!"
}}
                    </pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 