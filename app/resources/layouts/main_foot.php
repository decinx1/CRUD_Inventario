<?php
    // Ubicación: TU_PROYECTO_RAIZ/app/resources/layouts/main_foot.php
?>
</main> <footer class="bg-dark text-white text-center text-lg-start p-4 mt-auto"> <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">Inventa Fácil</h5>
                <p>
                    Gestionando tus activos tecnológicos de manera eficiente.
                </p>
            </div>

            <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                <h5 class="text-uppercase">Contacto</h5>
                <ul class="list-unstyled mb-0">
                    <li><i class="bi bi-geo-alt-fill"></i> Manzanillo, Colima, México</li>
                    <li><i class="bi bi-telephone-fill"></i> +52 313 193 2021</li>
                    <li><i class="bi bi-envelope-fill"></i> decinxsoft@gamail.com</li>
                </ul>
            </div>

             <div class="col-lg-3 col-md-12 mb-4 mb-md-0 text-center text-lg-end">
                <p class="mb-1 mt-lg-5">&copy; <?= date('Y') ?> Inventa Fácil</p>
                <p class="small mb-0">
                    <a href="#" class="text-white-50">Política de Privacidad</a> |
                    <a href="#" class="text-white-50">Términos de Servicio</a>
                </p>
            </div>
        </div>
    </div>
</footer>


</footer>

<script type="text/javascript">
    const URL_BASE_PARA_JS = "<?= URL ?>";
</script>
<script src="<?= JS_URL ?>jquery.js"></script>
<script src="<?= JS_URL ?>bootstrap.js"></script>
<script src="<?= JS_URL ?>sweetalert2.js"></script>
<script src="<?= JS_URL ?>app.js"></script>

</body>
</html>