<aside class="sidebar">
    <div class="sidebar-title"></div>
	
	    <!-- Beit Hatfuzut section -->

    <?php $bh_title = get_field('acf-options-BH-title' , 'options'); ?>
    <?php if ( $bh_title ): ?>
    
                    <div class="row">
                        <div class="col-sm-3">
                            <h2 class="title red"><span class="bh-translate"><?php echo $bh_title; ?></span></h2>
                        </div>
                    </div>

                    <div class="dictionary-term-translation">
                        <?php the_field('acf-options-BH-text' , 'options');?>
                    </div>
    <?php endif; ?>
	

    <!-- Dictionary section -->

    <?php if ( !is_page_template('dictionary') ): ?>
        <?php $story_dictionary = BH_dictionary_get_post_terms( get_the_id() );  ?>

        <?php  if ( empty( $story_dictionary ) ): ?>
            <?php $story_dictionary = BH_dictionary_get_all_terms( 5 , true );  ?>
        <?php endif; ?>

                    <div class="row">
                        <div class="col-sm-3">
                            <h2 class="title orange"><span class="bh-translate"><?php _e( 'מהמילון'  , 'BH'); ?></span></h2>
                        </div>
                    </div>

                    <div class="dictionary-content">
                        <p>
                            <?php  foreach ( $story_dictionary as $dictionary_term ): ?>
                                        <span class="dictionary-term-title"><?php echo stripslashesFull( $dictionary_term->dictionary_term ); ?></span><br>
                                        <span class="dictionary-term-translation"><?php echo stripslashesFull( $dictionary_term->dictionary_value );  ?></span>
                                <br/><br/>
                            <?php endforeach; ?>
                            <div class="dictionary-read-more">
                                <?php $dictionary_page_url = get_field( 'acf-options-dictionary-page-url' , 'options' );?>
                                <?php if ( $dictionary_page_url ): ?>
                                    <a href="<?php echo $dictionary_page_url; ?>">
                                        <?php _e('עוד מהמילון' , 'BH' ); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </p>
                    </div>



    <?php endif; ?>

    <!-- Quotes section -->
    <?php if ( !is_page_template('quotes') ): ?>
        <?php  $story_quote = BH_quotes_get_post_quotes( get_the_id() ); ?>

        <?php  if ( empty( $story_quote ) ): ?>
            <?php $story_quote = BH_quotes_get_all_quotes_random( 5 , true );  ?>
        <?php endif; ?>


                        <div class="row">
                            <div class="col-sm-3">
                                <h2 class="title cyan"><span class="bh-translate"><?php _e( 'ציטוט נבחר' , 'BH'); ?></span></h2>
                            </div>
                        </div>

                        <div class="quotes-content">
                            <p>

                                <?php  foreach ( $story_quote as $quote ): ?>

                                                <span class="quotes-text">"<?php echo stripslashesFull( trim($quote->quote_value) ); ?>"</span>
                                                <br/><br/>

                                 <?php endforeach; ?>

                                <div class="quotes-read-more">
                                                <?php $quotes_page_url = get_field( 'acf-options-quotes-page-url' , 'options' );?>
                                                <?php if ( $quotes_page_url ): ?>
                                                    <a href="<?php echo $quotes_page_url; ?>">
                                                        <?php _e( 'עוד ציטוטים', 'BH' ); ?>
                                                    </a>
                                                <?php endif; ?>
                               </div>

                             </p>
                        </div>


    <?php endif; ?>


    <!-- Recent stories section -->

<?php if ( !is_page_template('stories') ): ?>

        <?php

        $args  = array (
            'post_type'      => STORY_POST_TYPE ,
            'post_status'    => 'publish',
            'posts_per_page' => 5 ,
        );

        $story_query = null;
        $story_query = new WP_Query($args);
?>
<?php if( $story_query->have_posts() ): ?>


            <div class="row">
                <div class="col-sm-3">
                    <h2 class="title red"><span class="bh-translate"><?php _e( 'פורסמו לאחרונה' , 'BH'); ?></span></h2>
                </div>
            </div>

            <div class="recent-stories-content">
                <p>

                    <?php while ( $story_query->have_posts()) : $story_query->the_post(); ?>
                        <span class="recent-stories-title">
                            <a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a>
                        </span>

                        <br/>
                         <?php
                            // Get the story meta
                            $stories_meta = get_story_meta_data();
                         ?>
                        <span class="recent-stories-author">
                            <?php echo $stories_meta[STORY_META_ARRAY_AUTHOR_NAME]['meta_title'] . ' ' .
                                       $stories_meta[STORY_META_ARRAY_AUTHOR_NAME]['meta_data']; ?>
                        </span>
                        <br/>

                        <span class="recent-stories-author">
                            <?php echo wp_trim_words(  get_field('acf-story-secondary-text') , 5) . '...';  ?>
                        </span>

                        <br/><br/>
                     <?php endwhile; ?>


                        <div class="recent-stories-read-more">
                            <a href="<?php echo get_post_type_archive_link('story'); ?>">
                                <?php _e( 'עוד סיפורים', 'BH' ); ?>
                            </a>
                        </div>


                </p>
            </div>



<?php endif; ?>
<?php endif; ?>

<?php wp_reset_postdata(); ?>



    <div class="row">
        <div class="col-sm-3">
            <h2 class="title blue"><span class="bh-translate"><?php _e( 'נושאי סיפור' , 'BH'); ?></span></h2>
        </div>
    </div>

    <div>
        <?php

        $terms = get_terms( SUBJECTS_TAXONOMY );
		
		asort( $terms );
        
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) )
        {
            $subjects_search_url = get_field( 'acf-options-search-subject' , 'options' );


            echo '<ul id="story-subjects">';
            foreach ( $terms as $term )
            {
                $term_link = $subjects_search_url . '?subjects[]=' . $term->term_id;

                echo '<li>' . '<a href="'  .$term_link . '" target="_blank">' .  $term->name . '</a></li>';
            }
            echo '</ul>';
        }

        ?>

    </div>

</aside>

