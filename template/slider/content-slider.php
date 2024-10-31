<div class="my-gallery-plugin-container">
    <script>
        window.myGalleryPluginSettings=window.myGalleryPluginSettings||[];
        window.myGalleryPluginSettings.push({
            gallery: <?php echo esc_html($boolToString($config->galleryMode))?>,
            item: <?php echo esc_html($config->items)?>,
            loop: <?php echo esc_html($boolToString($config->loop))?>,
            thumbItem: <?php echo esc_html($config->thumbsNumber)?>,
            enableDrag: true,
            enableTouch:true,
            adaptiveHeight: false,
            galleryMargin: 5,
            thumbMargin: 5,
            vertical:false,
        });
  
    </script>
        <div class="photo-thumb-gallery">
            <?php if(!empty($title)): ?>
                <h2 class='my-gallery-plugin-title'><?php echo esc_html($title)?></h2>
            <?php endif; ?>
           
                <ul id="imageGallery" class="my-gallery-list" >
                    <?php foreach($images as $image): ?>
                        <li data-thumb="<?php echo $image->url->thumbnail[0]; ?>"  data-src="<?php echo $image->url->full[0]; ?>" >
                        <a href="<?php echo $image->url->full[0]; ?>" 
                            ref="nofollow" 
                            <?php if($image->title): ?>
                                    title="<?php echo esc_html($image->title); ?>" 
                            <?php endif; ?>
                        >
                            <img 
                                src="<?php echo $image->url->thumbnail[0]; ?>" 
                                height="<?php echo $image->url->full[2]; ?>" 
                                width="<?php echo $image->url->full[1]; ?>" 
                                <?php if($image->alt):?>
                                    alt="<?php echo esc_html($image->alt);?>" 
                                <?php endif;?>
                            >
                            <?php if($image->title): ?> 
                                <div class='image-cation'>
                                    <span><?php echo esc_html($image->title); ?></span>
                                </div>
                            <?php endif ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
       
        </div> <!--  photo-thumb-gallery -->
    </div> <!--  gallery -->
    

</body>
