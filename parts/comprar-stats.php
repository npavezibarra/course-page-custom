<?php

function mostrar_comprar_stats() {
    // Obtener el ID del usuario y el curso
    $user_id = get_current_user_id();
    $course_id = get_the_ID();
    
    // Verificar si el usuario está inscrito en el curso
    $is_enrolled = sfwd_lms_has_access($course_id, $user_id);

    // Obtener el valor del metabox (el quiz inicial asociado)
    $first_quiz_id = get_post_meta($course_id, '_first_quiz_meta_key', true);
    $first_quiz_url = get_permalink($first_quiz_id); // Obtener el URL del quiz inicial

    if (!is_user_logged_in() || !$is_enrolled) {
        // Primer estado: Usuario no logueado o no ha comprado el curso
        ?>
        <div class="progress-widget" style="display: flex; align-items: center; background-color: #f5f5f5; padding: 10px 20px; border-radius: 10px; width: 100%;">
            <div class="progress-bar" style="flex: 1; width: 50%; margin-right: 20px;">
                <div style="background-color: #e0e0e0; height: 10px; border-radius: 5px; position: relative;">
                    <div style="width: 0%; background-color: #ccc; height: 100%; border-radius: 5px;"></div> <!-- Barra vacía -->
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 12px; color: #333;">
                    <span>0%</span>
                    <span>100%</span>
                </div>
            </div>
            <div class="buy-button" style="flex: 1; width: 50%; text-align: right;">
                <button style="width: 80%; background-color: #4c8bf5; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-size: 14px; cursor: pointer;">
                    Tomar Curso
                </button>
            </div>
        </div>
        <?php
    } else {
        // Segundo estado: Usuario logueado y ya inscrito en el curso
        ?>
        <div class="progress-widget" style="display: flex; align-items: center; background-color: #f5f5f5; padding: 10px 20px; border-radius: 10px; width: 100%;">
            <div class="progress-bar" style="flex: 1; width: 50%; margin-right: 20px;">
                <div style="background-color: #e0e0e0; height: 10px; border-radius: 5px; position: relative;">
                    <div style="width: 30%; background-color: #00a65a; height: 100%; border-radius: 5px;"></div> <!-- Barra con progreso al 30% -->
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 12px; color: #333;">
                    <span>30%</span>
                    <span>100%</span>
                </div>
            </div>
            <div class="test-buttons" style="flex: 1; width: 50%; text-align: right; display: flex; justify-content: space-between;">
                <!-- Primer Test con link al Quiz inicial desde el metabox -->
                <a href="<?php echo esc_url($first_quiz_url); ?>" style="width: 45%; background-color: #4c8bf5; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-size: 14px; text-align: center; display: inline-block;">
                    Primer Test
                </a>
                <button style="width: 45%; background-color: #ccc; color: #333; border: none; padding: 10px 20px; border-radius: 5px; font-size: 14px; cursor: not-allowed;">
                    Segundo Test
                </button>
            </div>
        </div>
        <?php
    }
}

if ($first_quiz_id) {
    $first_quiz_url = get_permalink($first_quiz_id); // Obtener el URL del quiz inicial
} else {
    $first_quiz_url = '#'; // O un enlace de error o default
}

?>