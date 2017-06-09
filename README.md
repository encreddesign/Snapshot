## Snapshot

First version of Snapshot - Version `1.0`

To use is very simple
```PHP
include_once('snapshot.php');
$snapshot = Snapshot::forge('http://encreddesign.github.io/')->build()->getSnapshot();

// converts to object
$decoded = json_decode($snapshot);
```

Object consits of the below properties
```PHP
$decoded->favicon

$decoded->title

$decoded->image

$decoded->description

$decoded->domain

$decoded->siteUrl
```

If an error occurs then the ```$decoded->error``` property will be present
