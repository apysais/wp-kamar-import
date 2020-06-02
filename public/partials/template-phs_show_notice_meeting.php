<!-- From Plugin -->
<div class="bootstrap-iso">

  <h2 class="notice-current-date"><?php echo \Carbon\Carbon::parse($current_date)->format('l dS '); ?> Of <?php echo \Carbon\Carbon::parse($current_date)->format(' F, Y'); ?></h2>

  <?php if ( $show_date_pagination == "1" ) : ?>
    <div class="notice-pagination">
      <div class="btn-group" role="group" aria-label="Pagination">
        <a class="btn btn-secondary" href="<?php echo $yesterday_date_uri;?>">Previous Day</a>
        <a class="btn btn-secondary" href="<?php echo $today_date_uri;?>">Today</a>
        <a class="btn btn-secondary" href="<?php echo $tomorrow_date_uri;?>">Next Day</a>
      </div>
    </div>
  <?php endif; ?>

  <div class="table-responsive">
    <!-- display array in pre -->
    <?php //wki_dd($meetings); ?>
    <table class="table table-hover meetings-table shortcode-list-tables">
      <thead class="thead-meetings">
        <tr class="trow-meetings">
          <th scope="col" class="col-label">Meeting / Practices</th>
          <th scope="col" class="col-location">Location</th>
          <th scope="col" class="col-content">Content</th>
          <th scope="col" class="col-when">When</th>
          <th scope="col" class="col-staff">Staff</th>
        </tr>
      </thead>
      <tbody class="tbody-meetings">
        <?php if ( $meetings ) : ?>
          <?php foreach( $meetings as $meeting ) : ?>
                  <tr>
                    <td class="level"><?php echo $meeting->level;?></td>
                    <td class="location"><?php echo $meeting->location;?></td>
                    <td class="content">
                      <h3 class="title"><?php echo $meeting->title;?></h3>
                      <?php echo $meeting->content;?>
                    </td>
                    <td class="date_time"><?php echo $meeting->format_date;?> <?php echo $meeting->time;?></td>
                    <td class="staff"><?php echo $meeting->staff;?></td>
                  </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="list-notices">
    <!-- display array in pre -->
    <?php //wki_dd($notices); ?>
    <div class="table-responsive">
      <table class="notices-table shortcode-list-tables">
        <thead class="thead-notices">
          <tr class="trow-notices">
            <th scope="col" class="col-label">Notices</th>
            <th scope="col" class="col-content">Content</th>
            <th scope="col" class="col-staff">Staff</th>
          </tr>
        </thead>
        <tbody class="tbody-notices">
          <?php if ( $notices ) : ?>
            <?php foreach( $notices as $notice ) : ?>
                    <tr>
                      <td class="level"><?php echo $notice->level;?></td>
                      <td class="content">
                        <h3 class="title"><?php echo $notice->title;?></h3>
                        <?php echo $notice->content;?>
                      </td>
                      <td class="staff"><?php echo $notice->staff;?></td>
                    </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>
