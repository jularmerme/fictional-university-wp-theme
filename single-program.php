<?php 

  get_header(); 

  pageBanner();
  

  while( have_posts() ) {
    the_post(); 
    
?>

    <div class="container container--narrow page-section">

      <div class="generic-content">

        <div class="metabox metabox--position-up metabox--with-home-link">
          <p>
            <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>">
              <i class="fa fa-home" aria-hidden="true"></i> All Programs </a> 
              <span class="metabox__main">
              <?php the_title(); ?>
              </span>
          </p>
        </div>

        <div class="generic-content">
          <?php the_content(); ?>
        </div>

      </div>

      <?php 

        echo '<hr class="section-break">';

        $postID = get_the_ID();

        $relatedProfessors = get_posts(array(
          'posts_per_page' => -1,
          'post_type'          => 'professor',
          'orderby'             => 'title',
          'order'                => 'ASC',
          'meta_query'       => array(
            array(
              'key'          => 'related_programs',
              'compare'   => 'LIKE',
              'value'       => $postID
            )
          )
        )); 

        if ( $relatedProfessors) {

          echo '<h3 class="headline headline--medium">' . get_the_title() .  ' Professors</h3>';
          echo '<ul class="professor-cards">';
          foreach( $relatedProfessors as $professor) {
      
        ?>
            
              <li class="professor-card__list-item">
                <a class="professor-card" href="<?php echo get_the_permalink($professor->ID); ?>">
                  <img class="professor-card__image" src="<?php echo get_the_post_thumbnail_url($professor->ID, 'professorPic'); ?>" alt="" srcset="">
                  <span class="professor-card__name"><?php echo get_the_title($professor->ID); ?></span>
                </a>
              </li>
            

      <?php  } 
          echo '</ul>';
      }
        
      wp_reset_postdata();

        echo '<hr class="section-break">';

        $todayDate = date('Ymd');

        $homepageEvents = new WP_Query(array(
          'posts_per_page' => 2,
          'post_type'          => 'event',
          'meta_key'          => 'event_date',
          'orderby'             => 'meta_value_num',
          'order'                => 'ASC',
          'meta_query'       => array(
            array(
              'key'         => 'event_date',
              'compare'  => '>=',
              'value'      => $todayDate,
              'type'       => 'numeric'
            ),
            array(
              'key'          => 'related_programs',
              'compare'   => 'LIKE',
              'value'       =>  $postID
            )
          )
        )); 

        if ( $homepageEvents->have_posts() ) { 

        echo '<h3 class="headline headline--medium">Upcoming ' . get_the_title() .  ' Events</h3>';

        while( $homepageEvents->have_posts() ) {
          $homepageEvents->the_post();
      ?>

        <div class="event-summary">
          <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
            <span class="event-summary__month"><?php 
              $eventDate = new DateTime(get_field('event_date')); 
              echo $eventDate->format('M'); 
            ?></span>
            <span class="event-summary__day"><?php echo $eventDate->format('d');  ?></span>
          </a>
          <div class="event-summary__content">
            <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
            <p><?php echo (has_excerpt()) ? the_excerpt() : wp_trim_words( get_the_content(), 18 ); ?> <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
          </div>
        </div>

      <?php } wp_reset_postdata(); } ?>
    </div>

  <?php } 

  get_footer(); 
  
  ?>
