<?php
    // Ubicación: TU_PROYECTO_RAIZ/app/resources/layouts/main_foot.php
?>
</main> <footer class="bg-dark text-white text-center text-lg-start p-4 mt-auto">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">Inventa Fácil</h5>
                <p class="small"> Gestionando tus activos tecnológicos de manera eficiente.
                </p>
            </div>

            <div class="col-lg-5 col-md-6 mb-4 mb-md-0"> <h5 class="text-uppercase">Contacto</h5>
                <ul class="list-unstyled mb-0 small"> <li><i class="bi bi-linkedin"></i> <a href="https://www.linkedin.com/in/gustavo-aguilar-decinx/" target="_blank" class="text-white-50">Gustavo Aguilar</a></li>
                    <li><i class="bi bi-envelope-fill"></i> <a href="mailto:decinxsoft@gmail.com" class="text-white-50">decinxsoft@gmail.com</a></li>
                    <li><i class="bi bi-github"></i> <a href="https://github.com/decinx1" target="_blank" class="text-white-50">Decinx1 en GitHub</a></li> </ul>
            </div>

            <div class="col-lg-3 col-md-12 mb-4 mb-md-0 text-center text-lg-end ms-auto">
            <p class="mb-1 small">&copy; <?= date('Y') ?> Inventa Fácil</p>
            <p class="small mb-0">
                <a href="<?= URL ?>paginas/privacidad" class="text-white-50">Política de Privacidad</a> |
                <a href="<?= URL ?>paginas/terminos" class="text-white-50">Términos de Servicio</a> </p>
        </div>
            </div>
        </div>
    </div>
</footer> <script type="text/javascript">
    const URL_BASE_PARA_JS = "<?= URL ?>"; // Pasa la URL base a JavaScript si tu app.js la necesita
</script>
<script src="<?= JS_URL ?>jquery.js"></script>
<script src="<?= JS_URL ?>bootstrap.js"></script> <script src="<?= JS_URL ?>sweetalert2.js"></script>
<script src="<?= JS_URL ?>app.js"></script>

</body>
</html>