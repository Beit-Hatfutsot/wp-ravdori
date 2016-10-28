<?php
/**
 * This view outputs all the team members posts.
 * If no posts exists, related message will be shown.
 *
 *
 * @package    views/team
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

// Get all the team members
$args = array(
                'post_type'        => TEAM_MEMBER_POST_TYPE ,
                'post_status'      => 'publish',
                'posts_per_page'   => -1,
             );

$team_query = null;
$team_query = new WP_Query($args);

if( $team_query->have_posts() ):



    while ( $team_query->have_posts() ) : $team_query->the_post();

        // Get all the team member fields;
        $phone     = get_field('acf-team-phone');
        $cellphone = get_field('acf-team-cellphone');
        $email     = get_field('acf-team-email');

?>

<div class="row voffset5">

    <div class="col-xs-2 team-member-image">
        <?php
            // Get and show the post thumbnail
            if ( has_post_thumbnail() )
            {
                $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
                the_post_thumbnail( 'thumbnail' );
            }
        ?>
    </div>

    <div class="col-xs-10">
        <h4><?php the_title(); ?></h4>
        <div><?php echo get_the_content(); ?></div>
<br/>
        <?php if ($phone): ?>
            <span>
                <?php echo __(' טלפון:' , 'BH' ); ?>
                <a href="tel:<?php echo $phone; ?>">
                    <?php echo $phone; ?>
                </a>
            </span>
            <br/>
        <?php endif; ?>


        <?php if ($cellphone): ?>
            <span>
                <?php echo __('נייד:' , 'BH' ); ?>
                <a href="tel:<?php echo $cellphone; ?>">
                    <?php echo $cellphone; ?>
                </a>
            </span>
            <br/>
        <?php endif; ?>


        <?php if ($email): ?>
            <span>
                <?php echo __( 'מייל:' , 'BH' ) . ' '; ?>
                <a href="mailto:<?php echo $email; ?>">
                    <?php echo $email; ?>
                </a>
            </span>
        <?php endif; ?>
    </div>


</div>


<?php endwhile; ?>

<?php else: ?>

    <div class="row voffset5">
        <div class="col-xs-12 text-center">

            <div class="alert alert-warning">
                <strong><?php _e( 'לא נמצאו אנשי צוות' , 'BH' );?></strong>
            </div>
        </div>
    </div>

<?php
endif;
wp_reset_postdata();
?>