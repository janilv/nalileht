<?php
	require("functions.php");
	// $memes = loadAllMemes();
	// var_dump($memes)
?>

<!DOCTYPE html>
<html>
<head>

	<meta charset="UTF-8">
	<link rel="stylesheet" href="main.css">
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<title>NaliLeht</title>
</head>
<body>
	<div class="wrap">
		<?php require("header.php")?>
		<div class="container" >
			<?= isset($_GET["id"]) ? loadMeme($_GET["id"]) : header("Location: index.php");	//exit(); ?>
			<?= isset($_GET["id"]) ? loadComments($_GET["id"]) : header("Location: index.php");	//exit(); ?>
			
			</div>
			
		</div>
	</div>
</body>
</html>
<script>
<?php if(isset($_SESSION['userId'])): ?>
	function Comment(id) {
		var message = $('#meme-comment-box').val();

		if(message.length > 1)  {
			$.ajax({
				url: "actions.php?action=comment&id="+id+"&message="+message,
				dataType: "json",
			}).done(function(data) {
					var comment = '<div class="comment"><h4><?= $_SESSION['userlogin'] ?></h4><br>'+message+'</div>';
					$('#comments').append(comment);
					$('#meme-comment-box').val('');

				   var commentcount = parseInt($('#comment_count').html());
				   commentcount += 1;
				   $('#comment_count').html(commentcount);
				}
			).fail(function(data) {
				alert('Kommentaari lisamisel tekkis viga.');
			});   
		} else {
			alert('Kommentaar peab olema vähemalt 2 tähemärki pikk');
		}

	}
<?php endif; ?>
	
function upvote(id) {
    $.ajax({
        url: "actions.php?action=upvote&id="+id,
        dataType: "json",
    }).done(function(data) {
           $('#upvote_'+id).prop('onclick',null).off('click');
           $('#downvote_'+id).prop('onclick',null).off('click');
           var upvotes = parseInt($('#upvote_count_'+id).html());
           upvotes += 1;
           $('#upvote_count_'+id).html(upvotes);
        }
    ).fail(function(data) {
        alert('Punkti lisamisel tekkis viga.');
    });
}
function downvote(id) {
    $.ajax({
        url: "actions.php?action=downvote&id="+id,
        dataType: "json",
    }).done(function(data) {
           $('#upvote_'+id).prop('onclick',null).off('click');
           $('#downvote_'+id).prop('onclick',null).off('click');
		   var upvotes = parseInt($('#upvote_count_'+id).html());
           upvotes -= 1;
           $('#upvote_count_'+id).html(upvotes);
        }
    ).fail(function(data) {
        alert('Punkti lisamisel tekkis viga.');
    });
}
</script>


