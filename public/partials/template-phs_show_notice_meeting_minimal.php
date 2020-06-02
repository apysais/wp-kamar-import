<!-- From Plugin Minimal -->
<div class="bootstrap-iso">

  <h2 class="notice-current-date"><?php echo \Carbon\Carbon::parse($current_date)->format('l dS '); ?> Of <?php echo \Carbon\Carbon::parse($current_date)->format(' F, Y'); ?></h2>

  <?php if ( $show_date_pagination ) : ?>
    <div class="notice-pagination">
      <div class="btn-group" role="group" aria-label="Pagination">
        <a class="btn btn-secondary" href="<?php echo $yesterday_date_uri;?>">Previous Day</a>
        <a class="btn btn-secondary" href="<?php echo $today_date_uri;?>">Today</a>
        <a class="btn btn-secondary" href="<?php echo $tomorrow_date_uri;?>">Next Day</a>
      </div>
    </div>
  <?php endif; ?>

  <div class="container">
    <div class="meeting-list">
      <?php if ( $meetings ) : ?>
        <ul>
          <?php foreach( $meetings as $meeting ) : ?>
            <li>
              <h3 class="title"><?php echo $meeting->title;?></h3>
              <?php echo $meeting->content;?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
    <div class="notice-list">
      <?php if ( $notices ) : ?>
        <ul>
          <?php foreach( $notices as $notice ) : ?>
            <li>
              <h3 class="title"><?php echo $notice->title;?></h3>
              <?php echo $notice->content;?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>

</div>
