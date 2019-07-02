<footer class="<?php echo ( is_singular( STORY_POST_TYPE ) ) ? 'shadow-box' : ''; ?>">

	<div class="container">
	
		<div class="row">
	
			<div class="col-lg-4 col-sm-6">
				<?php
					// footer menu
					get_template_part('views/components/footer-menu');
				?>
			</div>
               
               <div class="col-lg-5 hidden-sm">
				<div class="nadav-logo">
                    <a href="http://www.bh.org.il/he/default.aspx" target="_blank">
                        <img src="<?php echo IMAGES_DIR?>/general/footer/logo-footer.png" />
                    </a>
					<div class="text-center">&copy; כל הזכויות שמורות</div>
				</div>
			  </div>
               
               <div class="col-lg-3 col-sm-6">
				<div class="footer-about">
                    <?php
                            echo __('בית הספר הבינלאומי ללימודי העם היהודי','BH') . '<br/>' .
                                 __( 'בית התפוצות - מוזיאון העם היהודי' , 'BH' ) . '<br/>' .
                                 __('ת.ד 39359, תל-אביב 61392','BH') . '<br/>' .
                                '<a href="mailto:kesher.rav.dori@bh.org.il">' . 'kesher.rav.dori@bh.org.il' . '</a>';
                    ?>
                </div>
			</div>
			
			  <div class="col-sm-12 hidden-lg hidden-md text-center">
				<div class="nadav-logo">
                    <a href="http://www.bh.org.il/he/default.aspx" target="_blank">
                        <img src="<?php echo IMAGES_DIR?>/general/footer/logo-footer.png" />
                    </a>
					<div class="text-center">&copy; כל הזכויות שמורות</div>
				</div>
			  </div>
			
		</div>
		
	

	</div>

</footer>