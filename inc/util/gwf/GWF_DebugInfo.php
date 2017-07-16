<?php
final class GWF_DebugInfo
{
	public static $INSTANCE;
	
	private $t1;
	
	public function __construct()
	{
		$this->t1 = microtime(true);
		self::$INSTANCE = $this;
	}
	
	public function data()
	{
		$totalTime = microtime(true) - $this->t1;
		$phpTime = $totalTime - GDODB::$QUERY_TIME;
		$memphp = memory_get_peak_usage(false);
		$memreal = memory_get_peak_usage(true);
		return array(
			'logWrites' => GWF_Log::$WRITES,
			'dbReads' => GDODB::$READS,
			'dbWrites' => GDODB::$WRITES,
			'dbCommits' => GDODB::$COMMITS,
			'dbQueries' => GDODB::$QUERIES,
			'dbTime' => round(GDODB::$QUERY_TIME, 4),
			'phpTime' => round($phpTime, 4),
			'totalTime' => round($totalTime, 4),
			'memory_php' => $memphp,
			'memory_real' => $memreal,
			'memory_max' => max($memphp, $memreal), # Bug in PHP?
		);
	}
	
	public function display()
	{
		$data = $this->data();
		return <<<EOT
<gwf-debug-info>
<section>
<h5>Database</h5>
<label>Queries</label><value>{$data['dbQueries']} ({$data['dbWrites']} writes, {$data['dbCommits']} commits)</value>
</section>
<section>
<h5>Memory</h5>
<label>Peak</label><value>{$data['memory_php']}</value>
<label>Real</label><value>{$data['memory_real']}</value>
</section>
<section>
<h5>Time</h5>
<label>Time Total</label><value>{$data['totalTime']}</value>
<label>Time DB</label><value>{$data['dbTime']}</value>
<label>Time PHP</label><value>{$data['phpTime']}</value>
</section>
</gwf-debug-info>
EOT;
	}
}