<div class="ui form">
	<label><p>1. go <a href="<?php echo $authorizeUrl?>" target="_blank"> here</a> to authorize app</p></label>
	<label><p>2. Click "Allow" (You might have to log in first) </p></label>
	<form action="index.php" method="post">
		<div class="inline field">
			<label>Authorization Code</label>
			<input type="text" name="authCode">
			<input type="button" class="ui button">
		</div>
	</form>
</div>