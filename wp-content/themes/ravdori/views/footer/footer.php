<footer class="<?php echo ( is_singular( STORY_POST_TYPE ) ) ? 'shadow-box' : ''; ?>">

	<div class="container">
	
		<div class="row">
	
			<div class="col-sm-4">
				<?php
					// footer menu
					get_template_part('views/components/footer-menu');
				?>
			</div>
               
               <div class="col-sm-5">
				<div class="nadav-logo">
                    <a href="http://www.bh.org.il/he/default.aspx" target="_blank">
                        <img src="<?php echo IMAGES_DIR?>/general/footer/logo-footer.png" />
                    </a>
				</div>
			</div>
               
               <div class="col-sm-3">
				<div class="footer-about">
                    <?php
                            echo __('בית הספר הבינלאומי ללימודי העם היהודי','BH') . '<br/>' .
                                 __( 'בית התפוצות - מוזיאון העם היהודי' , 'BH' ) . '<br/>' .
                                 __('ת.ד 39359, תל-אביב 61392','BH') . '<br/>' .
                                '<a href="mailto:kesher.rav.dori@bh.org.il">' . 'kesher.rav.dori@bh.org.il' . '</a>';
                    ?>
                </div>
			</div>
			
		</div>
		
	

	</div>

</footer>