<ul class="news_list">
<?php
  $query=mysql_query("SELECT * FROM `news_feed`ORDER BY `news_feed`.`news_id` ASC LIMIT 0 , $resultsPerPage");
  while($data=mysql_fetch_array($query)){
  $title=$data['news_title'];
  $content=$data['news_description'];
  echo "<li><h3>$title</h3><p>$content<p></li>";
  }
?>
<li class="loadbutton"><button class="loadmore" data-page="2">Load More</button></li>
</ul>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).on('click','.loadmore',function () {
  $(this).text('Loading...');
    var ele = $(this).parent('li');
        $.ajax({
      url: 'loadmore.php',
      type: 'POST',
      data: {
              page:$(this).data('page'),
            },
      success: function(response){
           if(response){
             ele.hide();
                $(".news_list").append(response);
              }
            }
   });
});
</script>