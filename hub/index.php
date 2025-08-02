<?php
require_once __DIR__ . '/includes/header.php';
?>

<section id="hero" class="relative min-h-screen flex items-center justify-center pt-20 pb-24">
    <div class="absolute inset-0 -z-10 bg-black"></div>
    <div class="text-center max-w-3xl px-6">
        <h1 class="text-4xl md:text-6xl font-extrabold leading-tight md:leading-tight">
            Monitor Your <span class="text-primary">Uptime</span> with Surgical Precision
        </h1>
        <p class="mt-6 text-lg md:text-xl text-gray-400">Enterprise-grade monitoring, instant alerts & detailed insights – so you can sleep at night.</p>
        <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
            <a href="register.php" class="px-8 py-3 rounded-md bg-primary hover:bg-primary/80 transition text-white font-medium">Start Free</a>
            <a href="#features" class="px-8 py-3 rounded-md border border-white/20 hover:border-primary text-gray-300 hover:text-primary transition">Learn More</a>
        </div>
    </div>
</section>

<section id="features" class="max-w-7xl mx-auto py-24 px-6">
    <h2 class="text-3xl md:text-4xl font-bold text-center mb-14">Everything you need – nothing you don’t</h2>
    <div class="grid md:grid-cols-3 gap-12" data-animate>
        <div class="bg-white/5 p-8 rounded-xl backdrop-blur-lg border border-white/10 hover:border-primary transition">
            <h3 class="text-xl font-semibold mb-3">Instant Multi-Channel Alerts</h3>
            <p class="text-gray-400 text-sm">Slack, SMS, Email – know before your users do.</p>
        </div>
        <div class="bg-white/5 p-8 rounded-xl backdrop-blur-lg border border-white/10 hover:border-primary transition">
            <h3 class="text-xl font-semibold mb-3">Global Performance Monitoring</h3>
            <p class="text-gray-400 text-sm">Synthetic checks from 25+ regions emulate real-world traffic.</p>
        </div>
        <div class="bg-white/5 p-8 rounded-xl backdrop-blur-lg border border-white/10 hover:border-primary transition">
            <h3 class="text-xl font-semibold mb-3">Security & Compliance</h3>
            <p class="text-gray-400 text-sm">SOC 2-ready infrastructure and end-to-end TLS encryption.</p>
        </div>
    </div>
</section>

<section id="pricing" class="py-24 bg-gradient-to-b from-black via-black to-gray-900">
    <div class="max-w-5xl mx-auto text-center px-6">
        <h2 class="text-3xl md:text-4xl font-bold mb-8">Simple, transparent pricing</h2>
        <div class="grid sm:grid-cols-2 gap-10" data-animate>
            <div class="border border-white/10 rounded-xl p-10 bg-white/5 hover:border-primary transition">
                <h3 class="text-xl font-semibold">Starter</h3>
                <p class="mt-4 text-4xl font-bold tracking-tight">Free</p>
                <ul class="mt-6 space-y-2 text-sm text-gray-400">
                    <li>5 Monitored URLs</li>
                    <li>10k Requests / mo</li>
                    <li>Email alerts</li>
                </ul>
            </div>
            <div class="border border-primary rounded-xl p-10 bg-primary/10 shadow-lg">
                <h3 class="text-xl font-semibold">Pro</h3>
                <p class="mt-4 text-4xl font-bold tracking-tight">$10<span class="text-lg font-normal">/mo</span></p>
                <ul class="mt-6 space-y-2 text-sm text-gray-200">
                    <li>Unlimited URLs</li>
                    <li>1M Requests / mo</li>
                    <li>Slack, SMS & Webhooks</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>