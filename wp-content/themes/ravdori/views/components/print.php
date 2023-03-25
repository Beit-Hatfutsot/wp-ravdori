<?php
/**
 * Show printing link of the plugin: "Print Friendly & PDF"
 *
 * @author     Htmline (Roy Hizkya)
 * @copyright  Copyright (c) 2015 Beit Hatfutsot Israel. (http://www.bh.org.il)
 * @version    1.0
 */
?>


<ul id="story-share-icons" class="text-left">
    <li class="social-icon pdf">
        <?php if( function_exists('pf_show_link') ){ echo pf_show_link(); } ?>
    </li>

    <li class="social-icon print">
        <?php if( function_exists('pf_show_link') ){ echo pf_show_link(); } ?>
    </li>

    <li class="social-icon mail">
        <div class="printfriendly">
            <a href="#lightbox" data-toggle="modal" rel="nofollow"  aria-label="שליחת איימיל">
             <span></span>
            </a>
        </div>
    </li>

</ul>