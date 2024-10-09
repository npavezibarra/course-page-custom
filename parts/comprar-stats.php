<?php
ob_start();

// Function to auto-enroll the user in a course
function auto_enroll_user_in_course($user_id, $course_id) {
    if (!sfwd_lms_has_access($course_id, $user_id)) {
        ld_update_course_access($user_id, $course_id);
    }
}

// Function to display the progress bar and buttons
function mostrar_comprar_stats() {
    // Obtener el ID del usuario y el curso
    $user_id = get_current_user_id();
    $course_id = get_the_ID();
    
    // Verificar si el usuario está inscrito en el curso
    $is_enrolled = sfwd_lms_has_access($course_id, $user_id);

    // Obtener el valor del metabox (el quiz inicial asociado)
    $first_quiz_id = get_post_meta($course_id, '_first_quiz_id', true);

    // Verificar que $first_quiz_id no esté vacío y obtener el enlace del quiz
    if (!empty($first_quiz_id)) {
        // Obtén el slug del quiz y genera manualmente la URL correcta
        $quiz_post = get_post($first_quiz_id);
        if ($quiz_post) {
            $first_quiz_url = home_url('/quizzes/' . $quiz_post->post_name . '/'); // URL corregida manualmente
        }
    } else {
        $first_quiz_url = '#'; // O un enlace predeterminado si no hay quiz asociado
    }

    // If user is not logged in or not enrolled
    if (!is_user_logged_in()) {
        // Usuario no logueado
        ?>
        <div class="progress-widget" style="display: flex; align-items: center; background-color: #eeeeee; padding: 20px 20px; border-radius: 10px; width: 100%;">
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
                <button style="width: 80%; background-color: #4c8bf5; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-size: 14px; cursor: pointer;"
                        onclick="window.location.href='<?php echo wp_login_url(get_permalink($course_id)); ?>'">
                    Iniciar Sesión para Inscribirse
                </button>
            </div>
        </div>
        <?php
    } elseif (!$is_enrolled) {
        // Usuario logueado pero no inscrito
        if (isset($_GET['enroll']) && $_GET['enroll'] == 1) {
            // Enroll the user when clicking the button
            auto_enroll_user_in_course($user_id, $course_id);
            wp_redirect(get_permalink($course_id));
            exit();
        }
        ?>
        <div class="progress-widget" style="display: flex; align-items: center; background-color: #eeeeee; padding: 20px 20px; border-radius: 10px; width: 100%;">
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
                <button style="width: 80%; background-color: #4c8bf5; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-size: 14px; cursor: pointer;"
                        onclick="window.location.href='<?php echo add_query_arg('enroll', 1, get_permalink($course_id)); ?>'">
                    Tomar Curso
                </button>
            </div>
        </div>
        <?php
    } else {
        // Usuario logueado y ya inscrito en el curso
        ?>
        <div class="progress-widget" style="display: flex; align-items: center; background-color: #eeeeee; padding: 20px 20px; border-radius: 10px; width: 100%;">
            <div class="progress-bar" style="flex: 1; width: 50%; margin-right: 20px;">
                <div style="background-color: #e0e0e0; height: 10px; border-radius: 5px; position: relative;">
                    <div style="width: 30%; background-color: #4c8bf5; height: 100%; border-radius: 5px;"></div> <!-- Barra con progreso al 30% -->
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 12px; color: #333;">
                    <span>30%</span>
                    <span>100%</span>
                </div>
            </div>
            <div class="test-buttons" style="flex: 1; text-align: right; display: flex; gap: 20px;">
                <!-- Primer Test con link al Quiz inicial desde el metabox -->
                <a href="<?php echo esc_url($first_quiz_url); ?>" style="flex: 1; background-color: #4c8bf5; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-size: 14px; text-align: center; display: inline-block; text-decoration: none;">
                    Primer Test
                </a>
                <button style="flex: 1; background-color: #ccc; color: #333; border: none; padding: 10px 20px; border-radius: 5px; font-size: 14px; cursor: not-allowed;">
                    Segundo Test
                </button>
            </div>
        </div>
        <?php
    }
}

if (headers_sent($file, $line)) {
    echo "Headers already sent in $file on line $line";
}

?>
