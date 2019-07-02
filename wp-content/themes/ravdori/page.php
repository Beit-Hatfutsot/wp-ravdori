<?php
/**
 * The Template for displaying default page
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header(); ?>

	<section class="page-content">

		<div class="container">

			<div class="row change-cols-order">

				<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 sidebar-col order-2">
					<?php get_template_part('views/sidebar/sidebar'); ?>
				</div>

				<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 order-1">
					<h2 class="title"><?php the_title(); ?></h2>

					<?php while ( have_posts() ) : the_post(); ?>
						<article class="post" id="post-<?php the_ID(); ?>">

							<div class="entry">
								<?php echo apply_filters('the_content',get_the_content()) ?>
							</div>

						</article>
					<?php endwhile;?>

				</div>

			</div>
		</div>

	</section>

<?php get_footer(); ?>