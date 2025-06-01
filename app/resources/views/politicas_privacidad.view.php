<?php
// Incluir el header base
include_once LAYOUTS . 'main_head.php';
setHeader($d);
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5">
                    <h2 class="mb-4 text-center">
                        <i class="bi bi-shield-lock text-primary"></i>
                        <?= htmlspecialchars($d->title ?? 'Políticas de Privacidad') ?>
                    </h2>
                    <p class="text-end text-muted"><strong>Última actualización:</strong> <?= htmlspecialchars($d->last_update ?? 'Fecha no disponible') ?></p>
                    <hr>
                    <p>En <strong>Inventa Fácil</strong>, nos comprometemos a proteger su privacidad. Esta política de privacidad describe cómo recopilamos, usamos y compartimos su información personal cuando utiliza nuestro sitio web y servicios.</p>
                    <div class="alert alert-warning">
                        <strong>Nota:</strong> Inventa Fácil se encuentra aún en desarrollo. No se autoriza la utilización de esta herramienta para el uso comercial.
                    </div>
                    <h4 class="mt-4"><i class="bi bi-person-lines-fill"></i> Información que Recopilamos</h4>
                    <p>Recopilamos información personal que usted nos proporciona directamente, como su nombre, correo electrónico y cualquier otra información que decida proporcionarnos al registrarse o utilizar nuestros servicios.</p>
                    <h4 class="mt-4"><i class="bi bi-gear"></i> Uso de la Información</h4>
                    <p>Utilizamos su información para proporcionar y mejorar nuestros servicios, comunicarnos con usted, procesar transacciones y cumplir con nuestras obligaciones legales.</p>
                    <h4 class="mt-4"><i class="bi bi-share"></i> Compartir Información</h4>
                    <p>No vendemos ni alquilamos su información personal a terceros. Podemos compartir su información con proveedores de servicios que nos ayudan a operar nuestro sitio web y servicios, siempre bajo estrictas condiciones de confidencialidad.</p>
                    <h4 class="mt-4"><i class="bi bi-shield-check"></i> Seguridad</h4>
                    <p>Implementamos medidas de seguridad razonables para proteger su información personal contra acceso no autorizado, divulgación o destrucción.</p>
                    <h4 class="mt-4"><i class="bi bi-arrow-repeat"></i> Cambios a esta Política</h4>
                    <p>Podemos actualizar esta política de privacidad ocasionalmente. Le notificaremos sobre cambios significativos mediante un aviso en nuestro sitio web.</p>
                    <h4 class="mt-4"><i class="bi bi-envelope"></i> Contacto</h4>
                    <p>Si tiene preguntas o inquietudes sobre nuestra política de privacidad, contáctenos a través del correo electrónico proporcionado en nuestro sitio web.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Incluir el footer base
include_once LAYOUTS . 'main_foot.php';
?>
