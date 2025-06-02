<?php
    // 1. Incluir la cabecera (main_head.php)
    include_once LAYOUTS . 'main_head.php';
    setHeader($d); 
?>



    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="card-title text-center text-primary mb-4">
                            <i class="bi bi-file-text-fill me-2"></i><?= htmlspecialchars($d->title ?? 'Términos de Servicio') ?>
                        </h2>
                        <p class="text-center text-muted mb-4">
                            <strong>Última actualización:</strong> <?= htmlspecialchars($d->last_update ?? date('d/m/Y')) ?>
                        </p>
                        <hr class="my-4">

                        <div class="alert alert-info text-center" role="alert">
                            <i class="bi bi-cone-striped me-2"></i>
                            <strong>Atención:</strong> Estos son términos de servicio de ejemplo y deben ser revisados y adaptados por un profesional legal si esta aplicación fuera a usarse en un entorno real. Para fines de este proyecto universitario, sirven como placeholder.
                        </div>

                        <p class="lead mt-4">
                            Bienvenido a <strong>Inventa Fácil</strong>. Estos términos y condiciones describen las reglas y regulaciones para el uso del sitio web y la aplicación de Inventa Fácil.
                        </p>

                        <section class="mt-5">
                            <h4 class="mb-3 text-secondary"><i class="bi bi-check-circle-fill me-2"></i>Aceptación de los Términos</h4>
                            <p>Al acceder y utilizar esta Aplicación, aceptas estar sujeto a estos Términos de Servicio y a nuestra Política de Privacidad. Si no estás de acuerdo con alguna parte de los términos, no podrás utilizar nuestros servicios.</p>
                        </section>

                        <section class="mt-4">
                            <h4 class="mb-3 text-secondary"><i class="bi bi-person-workspace me-2"></i>Uso de la Aplicación</h4>
                            <p>Inventa Fácil te otorga una licencia limitada, no exclusiva, intransferible y revocable para utilizar la Aplicación estrictamente de acuerdo con estos términos y para los fines previstos de gestión de inventario de equipos de cómputo en el contexto del proyecto universitario.</p>
                            <p>No debes usar la Aplicación para ningún propósito ilegal o no autorizado. Te comprometes a no:</p>
                            <ul class="list-group list-group-flush mb-3">
                                <li class="list-group-item">Intentar descompilar o realizar ingeniería inversa de cualquier software contenido en la Aplicación.</li>
                                <li class="list-group-item">Utilizar la Aplicación de forma que pueda dañar, deshabilitar, sobrecargar o perjudicar el sitio o interferir con el uso y disfrute de la Aplicación por parte de terceros.</li>
                                <li class="list-group-item">Utilizar la Aplicación para fines comerciales, ya que está destinada a ser un proyecto educativo.</li>
                            </ul>
                        </section>

                        <section class="mt-4">
                            <h4 class="mb-3 text-secondary"><i class="bi bi-shield-slash-fill me-2"></i>Limitación de Responsabilidad</h4>
                            <p>La Aplicación se proporciona "tal cual" y "según disponibilidad". En la máxima medida permitida por la ley aplicable, Inventa Fácil renuncia a todas las garantías, expresas o implícitas, en relación con la Aplicación y tu uso de la misma. No garantizamos que la Aplicación sea segura, esté libre de errores o virus, o que funcione sin interrupciones.</p>
                            <p>Dado que este es un proyecto universitario, su funcionalidad y seguridad no están garantizadas para un entorno de producción.</p>
                        </section>

                        <section class="mt-4">
                            <h4 class="mb-3 text-secondary"><i class="bi bi-arrow-repeat me-2"></i>Modificaciones a los Términos</h4>
                            <p>Nos reservamos el derecho de modificar estos términos en cualquier momento. Te notificaremos de cualquier cambio publicando los nuevos Términos de Servicio en esta página. Se te aconseja revisar estos Términos de Servicio periódicamente para cualquier cambio.</p>
                        </section>

                        <section class="mt-4">
                            <h4 class="mb-3 text-secondary"><i class="bi bi-envelope-fill me-2"></i>Contacto</h4>
                            <p>Si tienes alguna pregunta sobre estos Términos de Servicio, por favor contáctanos en: <a href="mailto:decinxsoft@gmail.com">decinxsoft@gmail.com</a>.</p>
                        </section>

                        <div class="text-center mt-5">
                            <a href="<?= htmlspecialchars($d->url_base ?? URL) ?>" class="btn btn-primary">
                                <i class="bi bi-arrow-left-circle-fill"></i> Volver a la Página Principal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
    // 3. Incluir el pie de página
    include_once LAYOUTS . 'main_foot.php';
?>