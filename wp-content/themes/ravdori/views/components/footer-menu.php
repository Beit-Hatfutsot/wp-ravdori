<div class="navbar" role="navigation">

	<nav class="footer-menu">
	
		<ul class="nav navbar-nav">
		
			<?php

				// footer menu
				$args = array(
					'theme_location'	=> 'footer-menu',
					'container'			=> false,
					'items_wrap'		=> '%3$s',
					'depth'				=> 1
				);
				wp_nav_menu($args);

			?>
			
		</ul>
		
	</nav>

</div>