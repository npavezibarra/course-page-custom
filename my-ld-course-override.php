<?php
/**
 * Plugin Name: My LearnDash Course Override
 * Description: Sobrescribe la plantilla single-sfwd-course.php de LearnDash con una versión personalizada.
 * Version: 1.0
 * Author: Nicolás Pavez
 */

// Reemplazar la plantilla del curso de LearnDash
function my_custom_ld_course_template( $template ) {
    if ( is_singular( 'sfwd-courses' ) ) {
        $custom_template = plugin_dir_path( __FILE__ ) . 'templates/single-sfwd-course.php';
        if ( file_exists( $custom_template ) ) {
            return $custom_template;
        }
    }
    return $template;
}
add_filter( 'template_include', 'my_custom_ld_course_template' );

// Encolar estilo de Course Page
function my_custom_ld_course_styles() {
    wp_enqueue_style( 'my-course-page-style', plugin_dir_url( __FILE__ ) . 'assets/course-page.css', array(), '1.0', 'all' );
}
add_action( 'wp_enqueue_scripts', 'my_custom_ld_course_styles' );

// Incluir metabox personalizado
include_once 'learndash-course-metabox.php';
include plugin_dir_path( __FILE__ ) . 'parts/comprar-stats.php';
include_once plugin_dir_path( __FILE__ ) . 'metabox-course-first-quiz.php';
include_once plugin_dir_path(__FILE__) . 'user-profile-photo.php';


?>
