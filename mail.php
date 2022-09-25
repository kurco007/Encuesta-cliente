<?php

/**
 * Plugin Name: ENCUESTA CLIENTES
 * Description:  Formulario para clientes. Utiliza el shortcode [etiqueta_mail] para que el formulario aparezca en la página o el post que desees.
 * Version:      1.2.3
 * Author:       Kurt Cruz Garcia
 * Author URI:   https:// kurtcruzgarcia.coms
 * PHP Version:
 *
 * @category Form
 * @package  KCG
 * @author   Kurt Cruz Garcia
 * @license  GPLv2 http://www.gnu.org/licenses/gpl-2.0.txt
 * @link     https://kurtcruzgarcia.com/
 */

register_activation_hook(__FILE__, 'encuesta_cliente_init');
/**
 *
 *
 * @return void
 */




function encuesta_cliente_init()
{

    global $wpdb;
    $tabla = $wpdb->prefix . 'encuesta';
    $charset_collate = $wpdb->get_charset_collate();
    //querydd
    $query = "CREATE TABLE IF NOT EXISTS $tabla(
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        nombre varchar(40)NOT NULL,
        telefono varchar (15) NOT NULL,
        correo varchar(100)NOT NULL,
		lugar smallint(4) NOT NULL,
        primer_compra smallint(2) NOT NULL,
        publicidad smallint(2) NOT NULL,
        ratio smallint(4) NOT NULL,
        comentarios text,
        ip varchar(300),
        created_at datetime NOT NULL,
        UNIQUE(id)
    )$charset_collate;";

    include_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($query);
}

add_shortcode('etiqueta_mail', 'Etiqueta_mail');

function Etiqueta_mail()
{
    global $wpdb;
    if (
        !empty($_POST)
        && $_POST['nombre'] != ''
        && $_POST['telefono'] != ''
        && is_email($_POST['correo'])
		&& $_POST['lugar'] !=''
        && $_POST['primer_compra'] != ''
        && $_POST['publicidad'] != ''
        && $_POST['ratio'] != ''
        && $_POST['comentarios'] != ''

    ) {
        $tabla = $wpdb->prefix . 'encuesta';
        $nombre = sanitize_text_field($_POST['nombre']);
        $telefono = sanitize_text_field($_POST['telefono']);
        $correo = $_POST['correo'];
		$lugar = $_POST['lugar'];
        $Primercompra = (int) $_POST['primer_compra'];
        $publicidad = (int) $_POST['publicidad'];
        $satisfaccionCliente = (int) $_POST['ratio'];
        $comentario = sanitize_text_field($_POST['comentarios']);
        $ip = encuesta_usuario();
        $created_at = date('Y-m-d H:i:s');

        $wpdb->insert(
            $tabla,
            array(
                'nombre' => $nombre,
                'correo' => $correo,
                'telefono' => $telefono,
                'primer_compra' => $Primercompra,
                'publicidad' => $publicidad,
                'ratio' => $satisfaccionCliente,
                'comentarios' => $comentario,
                'ip' => $ip,
                'created_at' => $created_at,
            )
        );
        echo "<p class='exito'><b>Tus datos han sido registrados</b>. Gracias
            por tu interés. En breve contactaré contigo.<p>";
    }

    // Carga esta hoja de estilo para poner más bonito el formulario
    wp_enqueue_style('css_mail', plugins_url('style.css', __FILE__));
    ob_start();
?>

<body class="cuerpo">
    <form action="<?php get_the_permalink(); ?>" method="post" id="formulario" class="formulario">
        <?php wp_nonce_field('graba_encuesta', 'encuesta_nonce'); ?>
        <div class="divicion">
            <div class="nom">
                <span class="titulo">Nombre</span>
                <input type="text" name="nombre" id="nombre" class="campo-form" autocomplete="off">
            </div>
            <div class="divicion">
                <span class="titulo">Telefono</span>
                <input type="tel" name="telefono" id="telefono" class="campo-form" autocomplete="off">
            </div>
            <div class="divicion">
                <span class="titulo">Correo</span>
                <input type="email" name="correo" id="correo" class="campo-form" autocomplete="off">
            </div>
            <div class="divicion">
                <span class="satis">¿Que tan satisfactoria fue su compra?</span> <br>
                <!----parte de las imgs-->
                <div id="panel" class="contenedor">
                    <div>
                        <label for="lugar" class="conte">
                            Reyes(Matriz)
                            <input class="op" type="radio" name="lugar" value="1"><span class="sp"></span> </label>
                        <label for="lugar" class="conte">
                            Morelos
                            <input class="op" type="radio" name="lugar" value="2"><span class="sp"></span>

                        </label> <br>
                    </div>
                    <div>
                        <label for="lugar" class="conte">
                            Tienda en linea
                            <input class="" type="radio" name="lugar" value="3"><span class="sp"></span>
                        </label>
                        <label for="lugar" class="conte">
                            Faceboock/Instagram/Whatsapp
                            <input class="" type="radio" name="lugar" id="4"><span class="sp"></span>

                        </label>
                    </div>
                </div>
                <div class="divicion">
                    <span class="titulo">¿Es su primera vez comprando con nosotros?</span> <br>
                    <div class="contenedor ">
                        <label for="primer_compra" class="conte">Si
                            <input class="op" type="radio" name="primer_compra" value="1"><span class="sp"> </span>
                        </label>
                        <label for="primer_compra" class="conte">
                            No
                            <input class="op" type="radio" name="primer_compra" value="2"><span class="sp"> </span> <br>
                        </label>
                    </div>
                    <div class="divicion">
                        <span class="titulo">¿Te gustaria recibir descuentos y promociones exclusivos en tu
                            correo?</span>
                        <br>
                        <div class="contenedor ">
                            <label for="publicidad" class="conte">Si
                                <input class="op" type="radio" name="publicidad" value="1"><span class="sp">
                                </span></label>
                            <label for="publicidad" class="conte">No
                                <input class="op" type="radio" name="publicidad" value="2"><span class="sp"> </span>
                                <br>
                            </label>
                        </div>
                    </div>
                    <div class="divicion">
                        <span class="satis">¿Que tan satisfactoria fue su compra?</span> <br>
                        <!----parte de las imgs-->
                        <div id="panel" class="contenedor-img">
                            <label for="ratio" class="separador">
                                <img src="https://sonoraboutique.com.mx/wp-content/uploads/2022/09/smiling.png"
                                    alt=""><br>
                                <input class="op" type="radio" name="ratio" value="1"><span class="sps"></span> <br>
                                <small>Muy
                                    bueno</small></label>
                            <label for="ratio" class="separador">
                                <img src="https://sonoraboutique.com.mx/wp-content/uploads/2022/09/smile.png"
                                    alt=""><br>
                                <input class="op" type="radio" name="ratio" value="2"><span class="sps"></span> <br>
                                <small>Bueno</small>
                            </label>
                            <label for="ratio" class="separador">
                                <img src="https://sonoraboutique.com.mx/wp-content/uploads/2022/09/neutral.png" alt="">
                                <br>
                                <input class="" type="radio" name="ratio" value="3"><span class="sps"></span> <br>
                                <small>Regular</small> </label>
                            <label for="ratio" class="separador">
                                <img src="https://sonoraboutique.com.mx/wp-content/uploads/2022/09/sad.png" alt=""> <br>
                                <input class="" type="radio" name="ratio" id="4"><span class="sps"></span> <br>
                                <small>Malo</small>
                            </label>
                        </div>
                        <!---fin de imgs-->
                    </div>
                    <div class="divicion">
                        <span class="conent titulo">Deja tu comentario:</span> <br>
                        <textarea class="campo-text" name="comentarios" id="" autocomplete="off"></textarea>
                    </div>
                    <div class="divicion btn">
                        <input type="submit" value="enviar" id="enviar" class="boton">
                    </div>

                </div>
    </form>
</body>
<?php
    return ob_get_clean();
}


add_action("admin_menu", "Admin_encuesta");
/**
 *agrega menu a barra lateral
 *
 * @return void
 */

function Admin_encuesta()
{
    add_menu_page("Encuesta a clienres", "EC", "manage_options", "encuestas_clientes", "encuestas_cliente_admin", "dashicons-feedback", 75);
}

function encuestas_cliente_admin()
{

    global $wpdb;
    $tabla = $wpdb->prefix . 'encuesta';
    $encuestas = $wpdb->get_results("SELECT * FROM $tabla");
    echo '<div class="wrap"><h1>Lista de clientes encuestados</h1>';
    echo '<table class="wp-list-table widefat fixed striped">';
    echo '<thead><tr><th width="10%">Nombre</th><th width="10%">Telefono</th><th>Correo</th><th>Lugar</th>';
    echo '<th>Primera vez comprando</th><th>Publicidad</th><th>Satisfaccion del cliente</th> <th>Comentario</th>';
    echo '</tr></thead>';
    echo '<tbody id="the-list">';
    foreach ($encuestas as $encuesta) {
        $nombre = esc_textarea($encuesta->nombre);
        $telefono = esc_textarea($encuesta->telefono);
		  $correo = esc_textarea($encuesta->correo);
		$lugar = (int) $encuesta->lugar;
        $Primercompra = (int) $encuesta->primer_compra;
        $publicidad = (int) $encuesta->publicidad;
        $satisfaccionCliente = (int) $encuesta->ratio;
		$comentario = esc_textarea($encuesta->comentarios);
        echo "<tr><td><a href='#' title='$comentario'>$nombre</a></td>";
        echo "<td>$correo</td><td>$lugar</td><td>$telefono</td><td>$Primercompra</td>";
        echo "<td>$publicidad</td><td>$satisfaccionCliente</td>";
        echo "<td>$comentario</td></tr>";
    }
    echo '</tbody></table></div>';
}

/**
 * Devuelve la IP del usuario que está visitando la página
 * Código fuente: https://stackoverflow.com/questions/6717926/function-to-get-user-ip-address
 *
 * @return string
 */
function encuesta_usuario()
{
    foreach (array(
        'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED',
        'REMOTE_ADDR'
    ) as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (array_map('trim', explode(',', $_SERVER[$key])) as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                    return $ip;
                }
            }
        }
    }
}