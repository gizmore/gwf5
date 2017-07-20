<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <atom:link href="<?= htmle($title_link) ?>" rel="self" type="application/rss+xml" />
    <title><?= htmle($feed_title); ?></title>
    <link><?= htmle($title_link); ?></link>
    <description><?= htmle($feed_description); ?></description>
    <language><?= $language; ?></language>
    <lastBuildDate><?= $build_date; ?></lastBuildDate>
    <pubDate><?= $pub_date; ?></pubDate>
    <image>
      <title><?= htmle($feed_title); ?></title>
      <url><?= htmle($image_url); ?></url>
      <link><?= htmle($title_link); ?></link>
      <width><?= $image_width; ?></width>
      <height><?= $image_height; ?></height>
    </image>
<?php foreach ($items as $item) : $item instanceof GWF_RSSItem; ?>
    <item>
      <title><?php echo GWF_RSS::displayCData($item->getRSSTitle()); ?></title>
<?php if ($link =  $item->getRSSLink()) echo sprintf('<link>%s</link>', htmle($link)).PHP_EOL; ?>
      <description><?php echo GWF_RSS::displayCData($item->getRSSDescription()); ?></description>
<?php if ($guid =  $item->getRSSGUID()) echo sprintf('<guid>%s</guid>', $guid).PHP_EOL; ?>
      <pubDate><?php echo GWF_RSS::displayDate($item->getRSSPubDate()); ?></pubDate>
    </item>
<?php endforeach; ?>
  </channel>
</rss>
