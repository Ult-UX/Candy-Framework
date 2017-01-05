@extends(layout) 

@block(title)<?php echo $title; ?>@end

@block(container)
<div class="row">
    <h1><?php echo $title; ?></h1>
    <?php echo lang(); ?>
    <hr/>
</div>
<div class="row">
    <div class="small-9 columns">
        <?php foreach($contents as $content): ?>
        <div class="media-object">
            <div class="media-object-section">
                <div class="thumbnail">
                    <img src="holder.js/192x96">
                </div>
            </div>
            <div class="media-object-section">
                <h4><a href="/article/<?php echo $content['slug']; ?>"><?php echo $content['title']; ?></a></h4>
                <p>I'm going to improvise. Listen, there's something you should know about me... about inception. An idea is
                    like a virus, resilient, highly contagious. The smallest seed of an idea can grow. It can grow to define
                    or destroy you.</p>
            </div>
        </div>
        <?php endforeach; ?>
        <hr/>
        <?php echo $pagination; ?>
    </div>
    <div class="small-3 columns">
        <ul class="vertical menu">
  <li>
    <a href="#">One</a>
    <ul class="nested vertical menu">
      <li><a href="#">One</a></li>
      <li><a href="#">Two</a></li>
      <li><a href="#">Three</a></li>
      <li><a href="#">Four</a></li>
    </ul>
  </li>
  <li><a href="#">Two</a></li>
  <li><a href="#">Three</a></li>
  <li><a href="#">Four</a></li>
</ul>
    </div>
</div>
@end