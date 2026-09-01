@extends('layouts.guest')

@section('content')
<div class="lp-container" style="max-width: 860px; padding: 48px 24px 64px;">
    <div style="text-align: center; margin-bottom: 36px;">
        <div class="section-eyebrow section-eyebrow--brand">Legal</div>
        <h1 class="section-title section-title--brand">Cookie Policy</h1>
        <p class="section-subtitle">How Piiston uses cookies and similar technologies on our landing page.</p>
    </div>

    <div style="display: flex; flex-direction: column; gap: 24px;">
        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-card); padding: 24px;">
            <h2 style="font-size: 18px; font-weight: 800; margin-bottom: 12px;">What are cookies?</h2>
            <p style="color: var(--text-muted); font-size: 14px; line-height: 1.7;">Cookies are small text files stored on your device when you visit a website. They are widely used to make websites work more efficiently, provide a better user experience, and give insights into how the site is used.</p>
        </div>

        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-card); padding: 24px;">
            <h2 style="font-size: 18px; font-weight: 800; margin-bottom: 12px;">How we use cookies</h2>
            <p style="color: var(--text-muted); font-size: 14px; line-height: 1.7;">Piiston uses cookies to ensure the essential functioning of our landing page and, with your consent, to understand usage patterns and improve our services.</p>
        </div>

        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-card); padding: 24px;">
            <h2 style="font-size: 18px; font-weight: 800; margin-bottom: 12px;">Cookie categories</h2>
            <div style="display: flex; flex-direction: column; gap: 16px; margin-top: 12px;">
                <div>
                    <div style="font-size: 14px; font-weight: 700; margin-bottom: 4px;">Essential cookies</div>
                    <div style="color: var(--text-muted); font-size: 14px; line-height: 1.7;">Required for the website to function properly. These include Laravel session cookies used for security and basic site functionality. They cannot be switched off.</div>
                </div>
                <div>
                    <div style="font-size: 14px; font-weight: 700; margin-bottom: 4px;">Analytics cookies</div>
                    <div style="color: var(--text-muted); font-size: 14px; line-height: 1.7;">Help us understand how visitors interact with the site so we can improve content and performance. These are only loaded if you accept cookies.</div>
                </div>
                <div>
                    <div style="font-size: 14px; font-weight: 700; margin-bottom: 4px;">Marketing cookies</div>
                    <div style="color: var(--text-muted); font-size: 14px; line-height: 1.7;">Used to track visitors across websites for advertising purposes. These are only loaded if you accept cookies.</div>
                </div>
            </div>
        </div>

        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-card); padding: 24px;">
            <h2 style="font-size: 18px; font-weight: 800; margin-bottom: 12px;">Consent and control</h2>
            <p style="color: var(--text-muted); font-size: 14px; line-height: 1.7;">When you first visit the Piiston landing page, a cookie consent banner will appear. You can accept or decline non-essential cookies at any time. Your choice is stored in a local cookie for 365 days. You can clear this cookie from your browser settings to reset your preference.</p>
        </div>

        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-card); padding: 24px;">
            <h2 style="font-size: 18px; font-weight: 800; margin-bottom: 12px;">Third-party services</h2>
            <p style="color: var(--text-muted); font-size: 14px; line-height: 1.7;">We may embed third-party services such as analytics providers or advertising partners. These services may set their own cookies. We encourage you to review the privacy policies of those services for more information.</p>
        </div>

        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-card); padding: 24px;">
            <h2 style="font-size: 18px; font-weight: 800; margin-bottom: 12px;">Contact us</h2>
            <p style="color: var(--text-muted); font-size: 14px; line-height: 1.7;">If you have questions about our cookie practices, please contact us at <a href="mailto:support@piiston.com" style="color: var(--accent-active);">support@piiston.com</a>.</p>
        </div>
    </div>
</div>
@endsection
