<?php

return [

    /*
    |---------------------------------------------------------------------------
    | Días de anticipación
    |---------------------------------------------------------------------------
    |
    | Cantidad mínima de días de anticipación con la que un docente debe
    | registrar una solicitud de préstamo respecto a la fecha de la clase.
    |
    */

    'dias_anticipacion' => (int) env('PRESTAMOS_DIAS_ANTICIPACION', 1),

    /*
    |---------------------------------------------------------------------------
    | Dominio institucional
    |---------------------------------------------------------------------------
    |
    | Dominio de correo institucional requerido para autenticarse con Google.
    | Cualquier cuenta cuyo email no termine en este dominio será rechazada.
    |
    */

    'institutional_domain' => env('INSTITUTIONAL_EMAIL_DOMAIN', 'unipaz.edu.co'),

];
