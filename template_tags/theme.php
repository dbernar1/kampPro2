<?php
function action_link( $url, $img_path, $label ) {
  $localized_label = lang( $label );
?>
  <a href="<?php echo $url ?>">
    <img
      title="<?php echo $localized_label ?>"
      src="<?php echo get_image_url( $img_path ) ?>"
      height="12"
      alt="<?php echo $localized_label ?>"
      />
  </a>
<?php
}

function formatted_date( $css_class, $date, $label ) { ?>
  <div class="<?php echo $css_class ?>">
    <span><?php echo lang('start date') ?>:</span>
    <?php
    if ($date->getYear() > DateTimeValueLib::now()->getYear()) {
      echo format_date($date, null, 0);
    } else {
      echo format_descriptive_date($date, 0);
    }
    ?>
  </div>
<?php  
}
