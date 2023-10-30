<div class="flex justify-center items-center">
    <span class="label-text mr-1">Dark mode</span>
    <input type="checkbox" class="toggle" id="theme-switcher">

    <script>
        const themes = {
            "light": "cupcake",
            "dark": "black",
        };

        const toggle = document.getElementById('theme-switcher');
        const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : null;

        if (currentTheme) {
            document.documentElement.setAttribute('data-theme', currentTheme);

            if (currentTheme === 'dark') {
                toggle.checked = true;
            }
        }

        toggle.addEventListener('change', function(e) {
            if (e.target.checked) {
                document.documentElement.setAttribute('data-theme', themes.dark);
                localStorage.setItem('theme', themes.dark);
            } else {
                document.documentElement.setAttribute('data-theme', themes.light);
                localStorage.setItem('theme', themes.light);
            }
        });
    </script>
</div>
