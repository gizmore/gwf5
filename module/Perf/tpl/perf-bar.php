<?php $i = GWF_DebugInfo::$INSTANCE->data(); ?>
<md-content layout="col" layout-fill flex layout-align="center">
  <?php printf('<span>%d Log, %d Qry, %d Wr, <b>%d Tr</b>', $i['logWrites'], $i['dbQueries'], $i['dbWrites'], $i['dbCommits']); ?>
  <?php printf('%.03fs DB + %.03fs PHP = <b>%.03fs</b>', $i['dbTime'], $i['phpTime'], $i['totalTime']); ?>
  <?php printf('<b>%.02f MB</b></span>', $i['memory_max']/(1024*1024)); ?>
</md-content>
