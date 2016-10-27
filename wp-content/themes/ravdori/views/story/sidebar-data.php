<div class="row visible-print">
<?php $story_dictionary = BH_dictionary_get_post_terms( get_the_id() );  ?>

<?php  if ( !empty( $story_dictionary ) ): ?>

    <div class="col-xs-6">
        <h3>מילון</h3>
                    <?php  foreach ( $story_dictionary as $dictionary_term ): ?>
                        <strong><span class="dictionary-term-title"><?php echo htmlentities( stripslashes($dictionary_term->dictionary_term) ); ?></span><br></strong>
                        <span class="dictionary-term-translation"><?php echo htmlentities( stripslashes($dictionary_term->dictionary_value) ); ?></span>
                        <br/><br/>
                    <?php endforeach; ?>
    </div>
<?php endif; ?>




<?php  $story_quote = BH_quotes_get_post_quotes( get_the_id() );  ?>
<?php  if ( !empty( $story_quote ) ): ?>

<div class="col-xs-6">

                <h3>ציטוטים</h3>
                <?php  foreach ( $story_quote as $quote ): ?>

                    <span class="quotes-text">”<?php echo htmlentities( stripslashes($quote->quote_value) ); ?>“</span>
                    <br/><br/>

                <?php endforeach; ?>

</div>
<?php endif; ?>
</div>
<div class="row visible-print">
    <div class="col-xs-12">
        <?php echo '<img src="' .  IMAGES_DIR . '/general/logo-mail.jpg" />';?>
    </div>
</div>