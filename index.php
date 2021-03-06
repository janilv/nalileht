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
		<?= loadAllMemes(); ?>
	</div>
</body>
</html>
<script>
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


