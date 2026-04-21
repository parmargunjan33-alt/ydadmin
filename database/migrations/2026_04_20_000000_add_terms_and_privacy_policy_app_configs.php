<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('app_configs')->insertOrIgnore([
            [
                'key' => 'terms_and_conditions',
                'value' => <<<'EOT'
<h1>Terms and Conditions</h1>

<h2>1. Acceptance of Terms</h2>
<p>By accessing and using this application, you accept and agree to be bound by the terms and provision of this agreement.</p>

<h2>2. User Responsibilities</h2>
<ul>
<li>You are responsible for maintaining the confidentiality of your account information.</li>
<li>You agree not to use the application for any unlawful or prohibited purposes.</li>
<li>You are responsible for all activities that occur under your account.</li>
</ul>

<h2>3. Intellectual Property Rights</h2>
<p>All content included in this application is the property of the application owner or its content suppliers. The compilation, arrangement, and assembly of all content on this application is the exclusive property of the application owner.</p>

<h2>4. Limitation of Liability</h2>
<p>The application is provided on an "as is" basis without warranties of any kind. The application owner will not be liable for any damages arising out of or in connection with the use of this application.</p>

<h2>5. Modifications</h2>
<p>The application owner reserves the right to modify these terms at any time without notice. Your continued use of the application following any modification constitutes your acceptance of the modified terms.</p>

<h2>6. Governing Law</h2>
<p>These terms and conditions are governed by and construed in accordance with the laws of the jurisdiction in which the application owner operates.</p>
EOT,
                'description' => 'Terms and Conditions for the application',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'privacy_policy',
                'value' => <<<'EOP'
<h1>Privacy Policy</h1>

<h2>1. Information We Collect</h2>
<p>We collect information you provide directly to us, such as:</p>
<ul>
<li>Name and contact information</li>
<li>Account credentials</li>
<li>Usage data and preferences</li>
<li>Payment information</li>
</ul>

<h2>2. How We Use Your Information</h2>
<p>We use the information we collect to:</p>
<ul>
<li>Provide, maintain, and improve our services</li>
<li>Process transactions and send related information</li>
<li>Send technical notices and support messages</li>
<li>Respond to your comments and questions</li>
<li>Send marketing communications (with your consent)</li>
</ul>

<h2>3. Data Security</h2>
<p>We implement appropriate technical and organizational measures to protect your personal data against unauthorized access, alteration, disclosure, or destruction.</p>

<h2>4. Third-Party Services</h2>
<p>Our application may contain links to third-party websites. We are not responsible for the privacy practices of these third parties.</p>

<h2>5. Your Privacy Rights</h2>
<p>You have the right to:</p>
<ul>
<li>Access your personal data</li>
<li>Correct inaccurate data</li>
<li>Request deletion of your data</li>
<li>Opt-out of marketing communications</li>
</ul>

<h2>6. Changes to This Privacy Policy</h2>
<p>We may update this privacy policy from time to time. We will notify you of significant changes by posting the new policy on our application.</p>

<h2>7. Contact Us</h2>
<p>If you have any questions about this privacy policy, please contact us at support@yourdomain.com.</p>
EOP,
                'description' => 'Privacy Policy for the application',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('app_configs')->whereIn('key', ['terms_and_conditions', 'privacy_policy'])->delete();
    }
};
