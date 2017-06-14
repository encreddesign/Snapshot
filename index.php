<?

  include_once('snapshot.php');

  $snapshot = Snapshot::forge('http://encreddesign.github.io/')->build()->getSnapshot();

  $decoded = json_decode($snapshot);

?>

<style>
  .snapshot {
    padding: 10px;
    display: block;
    border-left: 3px solid #d0d0d0;
    background-color: #f9f9f9;
  }
  .snapshot:after {
    content: '';
    display: block;
    clear: both;
  }
  .snapshot .left {
    display: block;
    width: 70%;
    float: left;
  }
  .snapshot .right {
    display: block;
    width: 30%;
    float: left;
  }
  .snapshot .top {
    margin-bottom: 5px;
    display: block;
  }
  .snapshot .favicon {
    display: inline-block;
    width: 15px;
    height: 15px;
    vertical-align: middle;
  }
  .snapshot .domain {
    display: inline-block;
    margin: 0px;
    font-family: 'Helvetica', Arial, sans-serif;
    font-size: 13px;
    font-weight: 100;
    color: #bb6d6d;
  }
  .snapshot .title {
    font-family: 'Helvetica', Arial, sans-serif;
    font-size: 13px;
    font-weight: 600;
    line-height: 18px;
    color: #3F51B5;
    text-decoration: none;
  }
  .snapshot .description {
    margin: 0px;
    font-family: 'Helvetica', Arial, sans-serif;
    font-size: 13px;
    line-height: 18px;
  }
  .snapshot .image {
    display: block;
    width: 100%;
    max-width: 100px;
  }
</style>

<? if(!isset($decoded->error)): ?>
  <div class="snapshot">

    <div class="left">
      <div class="top">
        <? if(($favicon = $decoded->favicon)): ?>
          <img class="favicon" src="<?= $favicon ?>" alt="">
        <? endif; ?>
        <? if(($domain = $decoded->domain)): ?>
          <h4 class="domain"><?= $domain ?></h4>
        <? endif; ?>
      </div>

      <div class="bottom">
        <? if(($title = $decoded->title)): ?>
          <a class="title" href="<?= $decoded->siteUrl ?>" target="_blank"><?= $title ?></a>
        <? endif; ?>
        <? if(($description = $decoded->description)): ?>
          <p class="description"><?= $description ?></p>
        <? endif; ?>
      </div>
    </div>

    <div class="right">
      <? if(($image = $decoded->image)): ?>
        <a href="<?= $decoded->siteUrl ?>" target="_blank"><img class="image" src="<?= $image ?>" alt="Site Image"></a>
      <? endif; ?>
    </div>

  </div>
<? else: ?>
  <p><?= $decoded->error ?></p>
<? endif; ?>
