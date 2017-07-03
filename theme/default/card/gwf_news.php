<?php $gdo instanceof GWF_News; ?>
<md-card>
  <md-card-title>
    <md-card-title-text>
      <span class="md-headline"><?php html($gdo->getTitle()); ?></span>
    </md-card-title-text>
  </md-card-title>
  <hr/>
  <md-card-content>
    <section layout="row" flex layout-fill>
      <p>
        <b><?php echo $gdo->getCreator()->renderCell(); ?></b>
        <b><?php lt($gdo->getCreateDate()); ?></b>
      </p>
    </section>
    <section layout="column" flex layout-fill>
      <?php echo $gdo->displayMessage(); ?>
    </section>
  </md-card-content>

  <md-card-actions layout="row" layout-align="end center">
  </md-card-actions>

</md-card>
