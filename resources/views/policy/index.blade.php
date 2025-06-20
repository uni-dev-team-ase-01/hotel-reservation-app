<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - Service Policy</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .policy-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 20px 0;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .content {
            padding: 40px;
        }

        .section {
            margin-bottom: 40px;
            padding: 30px;
            background: rgba(248, 249, 250, 0.8);
            border-radius: 15px;
            border-left: 5px solid #667eea;
            transition: all 0.3s ease;
        }

        .section:hover {
            transform: translateX(5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .section h2 {
            color: #2c3e50;
            font-size: 1.8rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-icon {
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .policy-list {
            list-style: none;
            padding: 0;
        }

        .policy-item {
            background: white;
            margin: 15px 0;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
            position: relative;
        }

        .policy-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .policy-item::before {
            content: counter(policy-counter);
            counter-increment: policy-counter;
            position: absolute;
            left: -15px;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .reservation-policies {
            counter-reset: policy-counter;
        }

        .policy-item h3 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 1.2rem;
        }

        .highlight {
            background: linear-gradient(120deg, #ffeaa7 0%, #fdcb6e 100%);
            padding: 2px 8px;
            border-radius: 5px;
            font-weight: 600;
        }

        .warning {
            background: linear-gradient(120deg, #fab1a0 0%, #e17055 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin: 10px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .success {
            background: linear-gradient(120deg, #00b894 0%, #00cec9 100%);
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin: 10px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 30px;
            margin-top: 40px;
        }

        .footer p {
            margin: 5px 0;
        }

        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .contact-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            text-align: left;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .header h1 {
                font-size: 2rem;
            }

            .content {
                padding: 20px;
            }

            .section {
                padding: 20px;
            }

            .policy-item {
                margin-left: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="policy-card">
            <div class="header">
                <h1>Booking System</h1>
                <p>Service Policies & Terms of Use</p>
            </div>

            <div class="content">
                <div class="section">
                    <h2>
                        <div class="section-icon">📋</div>
                        Overview
                    </h2>
                    <p>Welcome to our comprehensive booking system. This platform manages reservations, customer
                        information, room assignments, and billing across our hotel chain. Please review the following
                        policies to understand how our service operates and your rights and responsibilities as a user.
                    </p>
                </div>

                <div class="section reservation-policies">
                    <h2>
                        <div class="section-icon">🏨</div>
                        Reservation Policies
                    </h2>

                    <ul class="policy-list">
                        <li class="policy-item">
                            <h3>Making Reservations</h3>
                            <p>Customers may make, change, or cancel reservations through our website. When booking, you
                                must provide accurate personal details, specify room type, number of occupants, and
                                arrival/departure dates. All information must be complete and truthful.</p>
                        </li>

                        <li class="policy-item">
                            <h3>Payment & Credit Card Policy</h3>
                            <p>Reservations can be secured with or without credit card details. <span
                                    class="highlight">Important:</span> Reservations without credit card information
                                will be <span class="highlight">automatically cancelled at 7:00 PM daily</span> if not
                                guaranteed.</p>
                            <div class="warning">
                                ⚠️ <strong>Automatic Cancellation:</strong> Unreserved bookings are cancelled daily at 7
                                PM without credit card guarantee.
                            </div>
                        </li>

                        <li class="policy-item">
                            <h3>No-Show Policy</h3>
                            <p>Customers who fail to arrive for their reservation will be charged as no-shows. <span
                                    class="highlight">Billing occurs automatically at 7:00 PM daily</span> for no-show
                                reservations.</p>
                        </li>

                        <li class="policy-item">
                            <h3>Check-In Process</h3>
                            <p>Our reservation clerks can check in customers with or without prior reservations. During
                                check-in, checkout dates may be modified, and room assignments are made.</p>
                            <div class="success">
                                ✅ <strong>Flexible Check-In:</strong> Walk-in customers welcome - reservations not
                                required.
                            </div>
                        </li>

                        <li class="policy-item">
                            <h3>Check-Out & Billing</h3>
                            <p>At checkout, customers may pay by cash or credit card, and customers receive a checkout
                                statement. <span class="highlight">Late checkout fees apply</span> - customers not
                                checked out by the designated time will be charged for an additional night.</p>
                        </li>

                        <li class="policy-item">
                            <h3>Additional Services & Charges</h3>
                            <p>Optional charges may include:</p>
                            <ul style="margin-left: 20px; margin-top: 10px;">
                                <li>• Restaurant charges</li>
                                <li>• Room service and laundry</li>
                                <li>• Telephone services</li>
                                <li>• Automatic key issuance</li>
                                <li>• Club facility access</li>
                            </ul>
                        </li>
                    </ul>
                </div>

                <div class="section">
                    <h2>
                        <div class="section-icon">💼</div>
                        Corporate & Travel Company Services
                    </h2>

                    <div class="policy-item">
                        <h3>Group Bookings & Corporate Rates</h3>
                        <p>Travel companies can secure block bookings at discounted rates for <span
                                class="highlight">one or more nights</span> when reserving <span class="highlight">three
                                or more rooms</span>. Bills for corporate bookings are charged directly to the travel
                            company account.</p>
                    </div>
                </div>

                <div class="section">
                    <h2>
                        <div class="section-icon">🏠</div>
                        Extended Stay Services
                    </h2>

                    <div class="policy-item">
                        <h3>Residential Suites</h3>
                        <p>For extended stays, customers may reserve residential suites instead of standard hotel rooms.
                            Guests can occupy suites for <span class="highlight">weekly or monthly periods</span> with
                            special rates. Payment is structured on a weekly or monthly basis rather than daily rates.
                        </p>
                    </div>
                </div>

                <div class="section">
                    <h2>
                        <div class="section-icon">⚖️</div>
                        Terms & Conditions
                    </h2>

                    <div class="policy-item">
                        <h3>Service Agreement</h3>
                        <p>By using our reservation system, you agree to these terms and conditions. We reserve the
                            right to modify policies with reasonable notice. All transactions are subject to our
                            standard billing and cancellation policies as outlined above.</p>
                    </div>

                    <div class="policy-item">
                        <h3>Data Privacy</h3>
                        <p>Customer information is collected solely for reservation and billing purposes. We maintain
                            strict confidentiality of personal details and payment information in accordance with
                            industry standards.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
