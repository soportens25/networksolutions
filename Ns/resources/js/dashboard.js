document.addEventListener("DOMContentLoaded", function () {
    function showSection(target) {
        document.querySelectorAll(".content-section").forEach(section => {
            section.classList.add("hidden");
        });
        document.getElementById(target).classList.remove("hidden");
    }

    document.addEventListener("click", function (event) {
        if (event.target.classList.contains("sidebar-link")) {
            event.preventDefault();
            showSection(event.target.dataset.target);
        }
    });

    // Mostrar por defecto la sección dashboard
    // showSection("dashboard");

    // Alternar menú de usuario
    document.getElementById("user-menu-toggle")?.addEventListener("click", function () {
        document.getElementById("user-menu")?.classList.toggle("hidden");
    });
});
