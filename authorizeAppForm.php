<div class="ui grid">
	<div class="six wide column">
		<div class="ui raised segment">
			<div class="ui form">
				<label><p>1. Click <a href="<?php echo $authorizeUrl?>" target="_blank"> here</a> to authorize app</p></label>
				<label><p>2. Click "Allow" (You might have to log in first) </p></label>
				<label><p>3. Copy the authorization code and paste it to the box below</p></label>
				<form action="index.php" method="post">
					<div class="inline field">
						<label>Authorization Code</label>
						<input type="text" name="authCode">
						<input type="submit" class="ui button" name="submit">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>