<?php
/**
 * Show a single story row
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2019 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
 
 
?>

<?php 
	
	#region Stroy Image
		
		// Save the placeholder by default 
		$placeholder_path = get_bloginfo('stylesheet_directory') . '/images/archive-placeholder.jpg';
		$story_thumbnail = '<img width="260" height="200" src="' . $placeholder_path . '" class="attachment-story-archive-thumb size-story-archive-thumb" alt="' . get_the_title() . '">';
		
		// Use adult and child image by default.
		// if not exist, use the image of the adult's past, else use the placeholder
		if ( ($image_adult_and_child = get_field('acf-story-images-adult-child')) ) 
		{
			$story_thumbnail = wp_get_attachment_image($image_adult_and_child , 'story-archive-thumb');				
		}
		else if ( ($image_adult_past = get_field('acf-story-images-adult-past')) ) 
		{
			$story_thumbnail = wp_get_attachment_image($image_adult_past , 'story-archive-thumb');
		}
		
	
	#endregion

?>
		
	
<article class="story-item col-xs-12 col-sm-6 col-md-4 col-lg-4 un-4-cols">
		

		 <div class="story-item__image">
            <a href="<?php the_permalink();?>" class="title"><?php echo $story_thumbnail; ?></a>
        </div>
		
		
		<section class="story-item__details">
			<header class="story-item__title">
					<a href="<?php the_permalink();?>" class="title"> 
						<?php the_title(); ?> 
					</a>			
			</header>
			
			 <div class="story-item__meta-data col-xs-10">
				<?php foreach ( $stories_meta as $story_meta ): ?>
					<div class="col-xs-6">
						<span>
						 <?php if ( $story_meta['meta_data'] AND $story_meta['class'] != 'story-date'): ?>
								 <strong><?php echo $story_meta['meta_title']; ?></strong>
								 <?php echo $story_meta['meta_data']; ?>
								<?php endif; ?>
						 </span>
					</div>
				<?php endforeach; ?>
			</div>
			
			<div class="story-item__read-more story-item__read-more--list col-xs-2">
				<a href="<?php the_permalink();?>" class="title">לחצו לקריאה</a>	
			</div>
			

                <div class="col-xs-12 story-item__subtitle">
				     <?php if ( ($subtitle = get_field('acf-story-secondary-text')) ): ?>
						<?php echo $subtitle; ?>
					 <?php endif; ?>
                </div>
            
			
			<div class="story-item__read-more story-item__read-more--grid col-xs-12">
				<a href="<?php the_permalink();?>" class="title">לחצו לקריאה</a>	
			</div>
			
			<div class="col-xs-12 voffset2 story-item__excerpt">
                     <?php echo wp_trim_words( strip_shortcodes(get_the_content()), 30 ); ?>
            </div>
			
         </section>

		 
</article>