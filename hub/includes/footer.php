</main>

    <footer class="py-12 border-t border-white/10 text-center text-sm text-gray-500">
        © <span id="year"></span> harm.city / HarmWatch - All rights reserved
    </footer>

    <script>
        const btn = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');
        if (btn && menu) {
            btn.addEventListener('click', () => menu.classList.toggle('hidden'));
        }

        const observer = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('animate-fade-in');
                    observer.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('[data-animate]').forEach(el => {
            el.classList.add('opacity-0', 'translate-y-6');
            observer.observe(el);
        });

        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
    <style>
        .animate-fade-in{transition:all 1s cubic-bezier(.4,0,.2,1);opacity:1!important;transform:translateY(0)!important;}
    </style>
</body>
</html>