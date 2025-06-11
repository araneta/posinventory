<html>

<head>
    <title>
        Email
    </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f9fc;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #ffffff;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .header {
            text-align: left;
            padding-bottom: 20px;
        }

        .header img {
            width: 100px;
        }

        .content {
            font-size: 16px;
            color: #333333;
            line-height: 1.5;
        }

        .content p {
            margin: 0 0 20px;
        }

        .button {
            display: inline-block;
            background-color: #635bff;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }

        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e6ebf1;
            font-size: 14px;
            color: #666666;
        }

        .footer a {
            color: #635bff;
            text-decoration: none;
        }

        .footer .social-icons {
            margin-top: 20px;
        }

        .footer .social-icons a {
            margin-right: 10px;
            color: #666666;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img alt="Stripe logo" height="40"
                src="{{ asset('assets/uploads') }}/{{ settings('logo') }}"
                width="100" />
        </div>
        <div class="content">
            {!! $data['message'] !!}
        </div>
        <div class="footer"
            style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e6ebf1; font-size: 14px; color: #666666;">
            <p>
                This email was sent to
                <a href="mailto:{{ settings('mail_from_address') }}" style="color: #635bff; text-decoration: none;">
                    {{ settings('mail_from_address') }}
                </a>
                . If you have any queries, please contact us at
                <a href="mailto:{{ settings('company_email') }}" style="color: #635bff; text-decoration: none;">
                    {{ settings('company_email') }}
                </a>
                .
            </p>
            <p>
                {{ settings('company_address') }}
            </p> 
            <p>
                {{ settings('footer_text') }}
            </p>            
        </div>
    </div>
</body>

</html>
