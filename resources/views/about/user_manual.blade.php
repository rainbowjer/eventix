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
                    <h2 class="mb-3">Welcome to EventiX! 🎟️</h2>
                    <p>Your all-in-one platform for discovering, booking, and managing event tickets. Whether you’re a fan or an organizer, this guide will help you get the most out of EventiX.</p>

                    <h3 class="mt-4">Table of Contents</h3>
                    <ul>
                        <li><b>1. Getting Started</b></li>
                        <li><b>2. User Guide 👤</b>
                            <ul>
                                <li>2.1. Register & Login</li>
                                <li>2.2. Explore Events</li>
                                <li>2.3. Book Tickets</li>
                                <li>2.4. Manage Your Bookings</li>
                                <li>2.5. Resell Tickets</li>
                                <li>2.6. Personalize Your Profile</li>
                                <li>2.7. Notifications & Reminders</li>
                                <li>2.8. Quick Tips</li>
                            </ul>
                        </li>
                        <li><b>3. Organizer Guide 🧑‍💼</b>
                            <ul>
                                <li>3.1. Organizer Access</li>
                                <li>3.2. Create & Manage Events</li>
                                <li>3.3. Track Sales & Reports</li>
                                <li>3.4. Handle Resell Requests</li>
                                <li>3.5. View Event Reviews</li>
                            </ul>
                        </li>
                        <li><b>4. Need Help? 💬</b></li>
                    </ul>

                    <hr>
                    <h4>1. Getting Started</h4>
                    <p>EventiX is designed for ease of use and a seamless ticketing experience. Enjoy modern features like profile picture uploads, smart notifications, and dark mode for comfortable browsing.</p>

                    <h4 class="mt-4">2. User Guide 👤</h4>
                    <h5 class="mt-3">2.1. Register & Login</h5>
                    <ul>
                        <li>Click <b>Register</b> on the homepage to create your account.</li>
                        <li>Already have an account? Click <b>Login</b> and enter your details.</li>
                        <li>Don’t forget to verify your email if prompted!</li>
                    </ul>

                    <h5 class="mt-3">2.2. Explore Events</h5>
                    <ul>
                        <li>Browse the homepage or use the 🔍 <b>search bar</b> to find events.</li>
                        <li>Filter by category, date, or location.</li>
                        <li>Click any event for full details: date, time, venue, and available seats.</li>
                    </ul>

                    <h5 class="mt-3">2.3. Book Tickets</h5>
                    <ul>
                        <li>On the event page, pick your seat(s) and ticket quantity.</li>
                        <li>Click <b>Book Now</b> or <b>Proceed to Checkout</b>.</li>
                        <li>Review your order and select a payment method.</li>
                        <li>Complete payment and receive your e-ticket instantly!</li>
                    </ul>

                    <h5 class="mt-3">2.4. Manage Your Bookings</h5>
                    <ul>
                        <li>Go to <b>My Bookings</b> in your profile.</li>
                        <li>Bookings are organized into:
                            <ul>
                                <li>🎫 <b>Active Tickets</b>: Upcoming events you’re attending.</li>
                                <li>🔄 <b>Resold Tickets</b>: Tickets you’ve successfully resold.</li>
                                <li>✅ <b>Past/Done Tickets</b>: Events you’ve already attended.</li>
                            </ul>
                        </li>
                        <li>Download or view your e-tickets anytime.</li>
                    </ul>

                    <h5 class="mt-3">2.5. Resell Tickets</h5>
                    <ul>
                        <li>In <b>My Bookings</b>, select a ticket to resell.</li>
                        <li>Click <b>Resell Ticket</b>, set your price, and submit.</li>
                        <li><b>Note:</b> You cannot resell a ticket if the event will start within 7 days. The system will alert you if you try.</li>
                        <li>You’ll be notified when your ticket is resold or if action is needed.</li>
                    </ul>

                    <h5 class="mt-3">2.6. Personalize Your Profile</h5>
                    <ul>
                        <li>Update your name, email, or <b>phone number</b> from your profile page.</li>
                        <li><b>Profile Picture:</b>
                            <ul>
                                <li>Upload a new photo (JPG/PNG, max 2MB).</li>
                                <li>Delete your current picture if you wish.</li>
                            </ul>
                        </li>
                        <li>Add events to your <b>Favorites</b> for quick access.</li>
                        <li>See your <b>Recently Viewed</b> events.</li>
                    </ul>

                    <h5 class="mt-3">2.7. Notifications & Reminders</h5>
                    <ul>
                        <li>Tap the 🔔 <b>notification bell</b> (top right) for updates on bookings, resell status, and reminders. Unread notifications show a red badge.</li>
                        <li><b>Event Reminders:</b>
                            <ul>
                                <li>Get notified:
                                    <ul>
                                        <li>7 days before your event</li>
                                        <li>1 hour before</li>
                                        <li>When the event starts</li>
                                    </ul>
                                </li>
                                <li>Reminders arrive in-app and by email—never miss an event!</li>
                            </ul>
                        </li>
                        <li>Enjoy improved dark mode for comfortable viewing at night.</li>
                    </ul>

                    <h5 class="mt-3">2.8. Quick Tips</h5>
                    <ul>
                        <li>⭐ Add events to your favorites for easy access later.</li>
                        <li>🌓 Switch to dark mode for a better night-time experience.</li>
                        <li>🖼️ Keep your profile picture fresh for a personal touch.</li>
                        <li>📧 Check your email for important updates and tickets.</li>
                    </ul>

                    <hr>
                    <h4>3. Organizer Guide 🧑‍💼</h4>
                    <h5 class="mt-3">3.1. Organizer Access</h5>
                    <ul>
                        <li>Log in with your organizer credentials.</li>
                        <li>Need access? Contact the EventiX admin.</li>
                    </ul>

                    <h5 class="mt-3">3.2. Create & Manage Events</h5>
                    <ul>
                        <li>Go to the <b>Organizer Dashboard</b>.</li>
                        <li>Click <b>Create Event</b> and fill in all details.</li>
                        <li>Edit or update events anytime from your dashboard.</li>
                    </ul>

                    <h5 class="mt-3">3.3. Track Sales & Reports</h5>
                    <ul>
                        <li>View ticket sales stats and export reports.</li>
                        <li>Monitor sales trends and attendee info.</li>
                    </ul>

                    <h5 class="mt-3">3.4. Handle Resell Requests</h5>
                    <ul>
                        <li>Review user resell requests in <b>Resell Management</b>.</li>
                        <li>Approve or reject requests, add notes if needed.</li>
                        <li><b>Note:</b> Users cannot submit resell requests for tickets if the event is less than 7 days away.</li>
                        <li>Users are notified via the bell (with red badge for new items).</li>
                    </ul>

                    <h5 class="mt-3">3.5. View Event Reviews</h5>
                    <ul>
                        <li>Read attendee reviews to improve future events.</li>
                    </ul>

                    <hr>
                    <h4>4. Need Help? 💬</h4>
                    <ul>
                        <li>Email: <a href="mailto:support@eventix.com">support@eventix.com</a></li>
                        <li>Visit the <b>Help</b> section on our website.</li>
                        <li>Organizers: Contact your EventiX account manager for dedicated support.</li>
                    </ul>

                    <div class="text-center mt-4">
                        <b>Thank you for choosing EventiX! Enjoy the show! 🎉</b>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 