<?php
/**
 * Testimonial Block template.
 *
 * @param array $block The block settings and attributes.
 */

// Load values and assign defaults.
$title = !empty(get_field( 'title' )) ? get_field( 'title' ) : 'Your title here...';
?>
<div class="testimonial-block">
    <h2><?php echo esc_html( $title ); ?></h2>
</div>