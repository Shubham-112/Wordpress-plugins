<?php
/**
 * Created by PhpStorm.
 * User: DarkShadow
 * Date: 04-01-2018
 * Time: 01:13
 */

function reorder_admin_jobs_callback(){
    $args = array(
        'post_type' => 'job',
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'no_found_row' => true,
        'post_status' => 'publish',
        'update_post_term_cache' => false,
        'post_per_post' => 50
    );
    $job_listing = new WP_Query($args);
    $plugins = get_plugins();
    echo '<pre>';
    var_dump($plugins);
    echo '</pre>';
    die();
    ?>

    <div id="job-sort" class="wrap">
        <div id="icon-job-admin" class="icon32"><br></div>
        <h2><?php _e('Sort Job Positions', 'wp-job-listing') ?><img src="<?php echo esc_url(admin_url().'/images/loading.gif'); ?>" alt=""></h2>
            <?php  if($job_listing->have_posts() ): ?>
                <p><?php _e('<strong>Note:</strong>This only effects the jobs listed using the shortcode function', 'wp-job-listing'); ?></p>
                <ul id="custom-type-list">
                    <?php while($job_listing->have_posts()): $job_listing->the_post(); ?>
                    <li id="<?php the_id(); ?>"><?php the_title(); ?></li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p><?php _e('You have no jobs to sort.', 'wp-jon-listing'); ?></p>
            <?php endif; ?>

    </div>

    <?php
}