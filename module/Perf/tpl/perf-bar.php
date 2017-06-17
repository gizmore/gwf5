<?php $i = GWF_DebugInfo::$INSTANCE->data(); ?>
<md-content layout="col" layout-fill flex layout-align="center">
  <?php printf('<span>%d Qry, %d Wr, <b>%d Tr</b>', $i['dbQueries'], $i['dbWrites'], $i['dbCommits']); ?>
  <?php printf('%.04fs DB + %.04fs PHP = <b>%.03fs</b>', $i['dbTime'], $i['phpTime'], $i['totalTime']); ?>
  <?php printf('<b>%.02fs MB</b></span>', $i['memory_real']/(1024*1024)); ?>
</md-content>
