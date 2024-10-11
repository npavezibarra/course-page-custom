<?php
get_header(); ?>


<div id="main-menu">
    <div id="logo-website">
        <?php
        // Mostrar el logo del sitio si está configurado, de lo contrario, mostrar el nombre del sitio.
        if (function_exists('the_custom_logo') && has_custom_logo()) {
            the_custom_logo(); // Mostrar el logo del sitio.
        } else {
            // Mostrar el nombre del sitio si no hay logo.
            echo '<h1>' . get_bloginfo('name') . '</h1>';
        }
        ?>
    </div>
    <nav id="menu-replica" class="is-responsive items-justified-right wp-block-navigation is-horizontal is-content-justification-right is-layout-flex wp-container-core-navigation-is-layout-1 wp-block-navigation-is-layout-flex">
        <?php
        // Cargar el menú de navegación principal.
        echo do_blocks( '<!-- wp:navigation {"overlayMenu":"never"} /-->' );
        ?>
    </nav>
    
</div>

<div id="body-content" 
    style="background-image: url(<?php 
        // Verificar si el post tiene una imagen destacada.
        if (has_post_thumbnail()) {
            // Obtener la URL de la imagen destacada.
            echo get_the_post_thumbnail_url(null, 'full'); // Obtener la URL de la imagen destacada en tamaño completo.
        } else {
            // En caso de no haber imagen destacada, puedes usar una imagen por defecto o un color.
            echo 'https://via.placeholder.com/1920x1080'; // URL de una imagen por defecto.
        }
    ?>); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div id="datos-generales-curso">
    <h1><?php the_title(); ?></h1> <!-- Título del curso dinámico -->
    <h4>Profesor <?php 
        $author_id = get_post_field( 'post_author', get_the_ID() );
        $first_name = get_the_author_meta( 'first_name', $author_id );
        $last_name = get_the_author_meta( 'last_name', $author_id );
        echo $first_name . ' ' . $last_name;
    ?></h4> <!-- Nombre del autor (profesor) dinámico con nombre y apellido -->
    </div>
    <!-- Puedes añadir más contenido aquí si es necesario -->
</div>

<div id="buy-button-stats">
    <?php
    if (function_exists('mostrar_comprar_stats')) {
        mostrar_comprar_stats(); // Llamada dentro del div.
    } ?>
</div>


<div id="about-course">
<div id="course-content">
    <h4>Contenido del curso</h4>
    <hr>
    <ul style="list-style-type: none; padding-left: 0;">
        <?php
        // Obtener el ID del curso actual
        $course_id = get_the_ID();

        // Verificar si es un curso válido de LearnDash
        if ($course_id) {
            // Obtener las lecciones asociadas al curso por orden de menú
            $lessons_query = new WP_Query(array(
                'post_type' => 'sfwd-lessons',
                'meta_key' => 'course_id',
                'meta_value' => $course_id,
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'posts_per_page' => -1, // Obtener todas las lecciones
            ));

            // Obtener el ID del usuario actual
            $user_id = get_current_user_id();

            // Crear la lista de lecciones
            if ($lessons_query->have_posts()) {
                while ($lessons_query->have_posts()) {
                    $lessons_query->the_post();
                    $lesson_id = get_the_ID();
                    
                    // Verificar si la lección está completada
                    $is_completed = learndash_is_lesson_complete($user_id, $lesson_id);
                    $circle_color_class = $is_completed ? 'completed' : 'not-completed';

                    // Mostrar cada lección con su círculo y nombre
                    echo '<li class="lesson-item ' . $circle_color_class . '" style="display: flex; align-items: center; margin-bottom: 10px;">';
                    echo '<span class="lesson-circle" style="width: 20px; height: 20px; border-radius: 50%; margin-right: 10px; background-color: ' . ($is_completed ? '#4c8bf5' : '#ccc') . ';"></span>';
                    echo '<a href="' . get_permalink($lesson_id) . '">' . get_the_title() . '</a>';
                    echo '</li>';
                }
            }

            // Reset post data después del query
            wp_reset_postdata();

           // Ahora, obtenemos los quizzes asociados al curso
           $quizzes = learndash_get_course_quiz_list($course_id);
           if (!empty($quizzes)) {
               echo '<hr>';
               foreach ($quizzes as $quiz) {
                   // Display the SVG icon before the quiz title
                    echo '<li style="display: flex; align-items: center;">';
                    echo '<img src="' . esc_url(plugins_url('assets/svg/exam-icon.svg', __DIR__)) . '" alt="Exam Icon" style="width: 20px; height: 20px; margin-right: 10px;">'; // Correct path to the SVG
                    echo '<a href="' . get_permalink($quiz['post']->ID) . '">' . esc_html($quiz['post']->post_title) . '</a>';
                    echo '</li>';
               }
               echo '</ul>';
           } else {
               echo '<p>No hay quizzes asociados a este curso.</p>';
           }
        }
        ?>
    </ul>
</div>

<div id="description-course">
    <h4>Sobre este curso</h4>
    <hr>
    <?php
    // Mostrar el contenido descriptivo del curso almacenado en el backend
    the_content();
    ?>
</div>

</div>



