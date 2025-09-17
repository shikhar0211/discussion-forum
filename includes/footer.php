 </main>
 <footer class="mt-auto bg-dark text-light py-4 border-top border-secondary-subtle">
     <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
         <p class="mb-0 small order-2 order-md-1">&copy; <?= date("Y") ?> My Forum</p>
         <ul class="nav order-1 order-md-2">
             <li class="nav-item"><a href="#" class="nav-link px-2 text-light">Contact Us</a></li>
             <li class="nav-item"><a href="#" class="nav-link px-2 text-light">Support</a></li>
             <li class="nav-item"><span class="nav-link px-2 text-light">+1 (800) 555-0100</span></li>
         </ul>
     </div>
 </footer>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    (function() {
        const root = document.documentElement;
        const stored = localStorage.getItem('theme');
        const currentTheme = stored || 'light';
        root.setAttribute('data-bs-theme', currentTheme);
        
        const btn = document.getElementById('themeToggle');
        if (!btn) return;
        
        // Set initial icon
        btn.innerHTML = currentTheme === 'light' ? '<i class="bi bi-moon-stars"></i>' : '<i class="bi bi-sun"></i>';
        
        btn.addEventListener('click', function () {
            const current = root.getAttribute('data-bs-theme') || 'light';
            const next = current === 'light' ? 'dark' : 'light';
            root.setAttribute('data-bs-theme', next);
            localStorage.setItem('theme', next);
            btn.innerHTML = next === 'light' ? '<i class="bi bi-moon-stars"></i>' : '<i class="bi bi-sun"></i>';
        });
    })();
</script>
</body>
</html>
