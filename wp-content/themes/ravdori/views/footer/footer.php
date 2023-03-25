<footer class="<?php echo ( is_singular( STORY_POST_TYPE ) ) ? 'shadow-box' : ''; ?>">

	<div class="container">
	
		<div class="row footer-wrapper">
	
			<div class="col-xs-12 col-sm-6 col-md-4 footer-menu-wrapper" style="max-width: 29%; padding-left:0;">
				<?php
					// footer menu
					get_template_part('views/components/footer-menu');
				?>
			</div>
               
               <div class="col-xs-12 col-sm-12 col-md-5 footer-logos-wrapper" style="padding:0;">
				<div class="nadav-logo">
                        
						<?php /*
						<div style="width:25%;float:right;">
									<a href="https://www.seeingthevoices.com/skn/c82/%D7%A8%D7%95%D7%90%D7%99%D7%9D_%D7%90%D7%AA_%D7%94%D7%A7%D7%95%D7%9C%D7%95%D7%AA"  target="_blank">
										<img src="<?php echo IMAGES_DIR?>/general/footer/voices-logo.png" alt=""/>
									</a>
						</div>
						*/ ?>

						
						<div style="width:25%;float:right;">
									<a href="https://www.gov.il/he/Departments/General/kesher" target="_blank" aria-label="המשרד לשיווין חברתי">
										<img src="<?php echo IMAGES_DIR?>/general/footer/siv-logo.png" alt="המשרד לשיווין חברתי"/>
									</a>
						</div>
						
						
						<div style="width:25%;float:right;">
									<a href="https://www.bh.org.il/he/%D7%97%D7%99%D7%A0%D7%95%D7%9A/%D7%94%D7%A7%D7%A9%D7%A8-%D7%94%D7%A8%D7%91-%D7%93%D7%95%D7%A8%D7%99/"  target="_blank" aria-label="הקשר הרב דורי">
										<img src="<?php echo IMAGES_DIR?>/general/footer/ravdori-logo.png" alt="הקשר הרב דורי"/>
									</a>
						</div>


						<div style="width:25%;float:right;">
								<a href="#" target="_blank" aria-label="משרד החינוך">
										<img src="<?php echo IMAGES_DIR?>/general/footer/edu-logo.jpg" alt="education"/>
								</a>
						</div>
								
		

					<div class="text-center" style="float: right;width: 100%;">&copy; כל הזכויות שמורות</div>
				</div>
			</div>
               
               <div class="col-xs-12 col-sm-6 col-md-3 footer-about-wrapper">
				<div class="footer-about">
                    <?php
					/*
                            echo __('בית הספר הבינלאומי ללימודי העם היהודי','BH') . '<br/>' .
                                 __( 'אנו - מוזיאון העם היהודי' , 'BH' ) . '<br/>' .
                                 __('ת.ד 39359, תל-אביב 61392','BH') . '<br/>' .
                                '<a href="mailto:kesher.rav.dori@bh.org.il">' . 'kesher.rav.dori@bh.org.il' . '</a>';
								*/
                    ?>
                </div>
			</div>
			
		</div>
		
	

	</div>

</footer>