<section id="homepage-terms" class="row voffset4">


    <?php
            $random_quote = BH_quotes_get_all_quotes_random ( 1 , true );

            if ( !empty( $random_quote) )
                $random_quote = $random_quote[0];

            $random_dictionary = BH_dictionary_get_all_terms ( 1 , true );

            if ( !empty( $random_dictionary) )
                $random_dictionary = $random_dictionary[0];
    ?>


    <div class="col-sm-12">
        <div class="row terms-hover">

            <div class="col-sm-2">
                <div class="homepage-terms-col">
                     <?php _e( 'ציטוט נבחר:' , 'BH' );?>
                </div>
            </div>

            <div class="col-sm-10">
                    <div class="term-title">
                        <?php
                            if ( !empty( $random_quote ) )
                                echo '"' . trim_non_utf8_letters( stripslashesFull( wp_trim_words ( $random_quote->quote_value , 120 ) ) ) . '"';
                            else
                                echo 'לא קיימים ציטוטים במערכת';
                        ?>
                    </div>
                    <br/>
                    <?php  if ( !empty( $random_quote ) ): ?>
                        <div class="term-link">
                            (<?php _e( 'מתוך: ' , 'BH' ); ?><a href="<?php echo get_the_permalink( $random_quote->post_id ); ?>"><?php echo stripslashes(get_the_title( $random_quote->post_id ));?></a>)
                        </div>
                    <?php endif; ?>
            </div>

        </div>


        <div class="row terms-hover voffset3">

                <div class="col-sm-2 ">
                    <div class="homepage-terms-col">
                        <?php _e( 'מהמילון: ' , 'BH' );?>
                    </div>
                </div>


                <div class="col-sm-10">

                    <?php  if ( !empty( $random_dictionary ) ): ?>
                    <div class="term-title"><?php echo stripslashes($random_dictionary->dictionary_term); ?> - </div>
                    <div class="term-value"><?php echo stripslashes(wp_trim_words ( $random_dictionary->dictionary_value , 20 )); ?></div>
                    <br/>
                    <div class="term-link">
                        (<?php _e( 'מתוך: ' , 'BH' ); ?><a href="<?php echo get_the_permalink( $random_dictionary->post_id ); ?>"><?php echo stripslashes(get_the_title( $random_dictionary->post_id ));?></a>)
                    </div>
                    <?php else: ?>
                        <div class="term-title">
                            לא קיימים ערכים במערכת
                        </div>
                    <?php endif; ?>
                </div>

        </div>


    </div>

</section>