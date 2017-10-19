<?php
/**
 * Template Name: Team
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header();
?>
<?php 
/*
require_once(FUNCTIONS_DIR . '/' . 'rest-api.php');


//$story  =  get_post_field('post_content', '40271');
$story  =  get_post_field('post_content', '40136');

echo '<h1>Story: </h1><br/><br/>';
var_dump($story);


$bhRestApi 	= new BH_BeitHatfutsotRestApi();
$bhRestApi->linkifyRoute = new BH_BeitHatfutsotRestApiRouteLinkifyRoute('http://devapi.dbs.bh.org.il/v1/linkify' , $story);

$linkifyRoute = $bhRestApi->linkifyRoute;

if ( $linkifyRoute->doRestApiCall() )
{
	$linkifyRoute->linkifyStory( $story );
}

exit;*/

?>

<section class="page-content">

    <div class="container">

        <div class="row">

            <div class="col-xs-4 sidebar-col">
                <?php
                get_template_part('views/sidebar/sidebar');
                ?>
            </div>

            <div class="col-xs-8">

                <h2 class="title"><?php the_title(); ?></h2>

                <article class="post" id="post-<?php the_ID(); ?>">
                    <?php
                        get_template_part('views/team/team');
                    ?>
                </article>

            </div>

        </div>
    </div>

</section>
<?php  get_footer(); ?>