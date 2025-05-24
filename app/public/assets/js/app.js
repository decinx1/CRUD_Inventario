$(document).ready(function() {
    console.log("app.js cargado y DOM listo.");

    // Inicializar todos los tooltips de Bootstrap en la página
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // ... (otro código que puedas tener en tu app.js) ...

    // Tu objeto app anterior (si lo sigues usando y es relevante aquí)
    // const miAppInventaFacil = { /* ... */ };
    // miAppInventaFacil.init();
});