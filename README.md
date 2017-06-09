## Snapshot

First version of Snapshot - Version `1.0`

To use is very simple
```PHP
include_once('snapshot.php');
$snapshot = Snapshot::forge('https://www.any.do/')->build()->getSnapshot();

// converts to object
$decoded = json_decode($snapshot);
```
