<?php
	
	use app\core\Utility;
	
	/**
	 * @var $this \app\core\View
	 */
	$this->title = 'OurPage - Read Contacts';
	
	if (!empty($messageObj))
	{
		// Utility::dieAndDumpPretty($messageObj);
		echo "<script> var messageId = {$messageObj->id}; </script>";
	} else
	{
		echo "<script> var messageId = null; </script>";
	}

?>
<!-- HTML code goes here for the view -->

<style>
	.message-container {
		width: 95%;
		border: 1px solid #262626;
		border-radius: 8px;
		margin: 8px auto 0 auto;
		padding: 8px;
		min-height: 75px;
		background: #ffffff;
		transition: all .4s ease;
		position: relative;
	}
	
	.message-container:hover,
	.message-container:active,
	.message-container:focus,
	.message-container.active {
		cursor: pointer;
		transition: all .4s ease;
		box-shadow: 5px 5px 3px rgba(0, 0, 0, .5);
	}
	
	.message-container .new-message-notifier {
		line-height: .5em;
		border-radius: 25%;
		background: #0d6efd; /* Fallback */
		background: var(--bs-blue);
		position: absolute;
		top: 10px;
		right: 10px;
		border-radius: 50%;
		padding-bottom: 4px;
		padding-top: 4px;
		padding-left: 4px;
		padding-right: 4px;
	}
	
	.message-container .message-subject {
		font-size: 1.5em;
		font-weight: 500;
	}
	
	.message-container .message-from {
		font-size: 1em;
		font-style: italic;
		font-weight: 300;
	}
	
	.message-container .message-preview {
		font-size: 1em;
		font-style: normal;
		font-weight: 100;
	}
</style>

<div class="row">
	<div class="col-3">
		<div class="sidebar"
		     style="background: #e0e0e0; position:fixed;top: 56px;left:0;bottom:0; max-width:24%;min-width:185px;">
			<!-- Message selection goes here -->
			<h3 class="border-bottom border-dark py-3">
				<div class="w-100 text-center"><?= count($data); ?> Contact Messages</div>
			</h3>
			<!-- Display cards with text for selecting the messages -->
			<?php if (count($data) > 0) : ?>
				<?php foreach ($data as $message) : ?>
					<div class="message-container" aria-label="Content message selection, click to open" role="link"
					     data-id="<?= $message->id; ?>"
					     onclick="event.preventDefault(); event.stopPropagation(); event.stopImmediatePropagation(); location.replace('/contact/read/' + this.dataset.id);">
						<?php if ($message->read === 0) : ?>
							<div class="new-message-notifier"></div>
						<?php endif; ?>
						<div class="message-subject">
							<?= $message->subject; ?>
						</div>
						<div class="message-from">
							<?= $message->email; ?>
						</div>
						<div class="message-preview">
							<?= strlen($message->body) > 50 ? substr($message->body, 0, 50) . "..." : $message->body; ?>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
	<div class="col-8 read-message">
		<?php if (!empty($messageObj->id)) : ?>
		<div class="row">
			<div class="col-5 offset-7" style="text-align: end;"
			">
			<button class="action btn btn-link" onclick="location.replace('/contact/read');">Close Message</button>
			<form action="/contact/read/<?= $messageObj->id; ?>" method="POST" class="d-inline">
				<input type="hidden" name="_method" value="PUT">
				<button class="action btn btn-link" onclick="this.parentElement.submit()">Mark Unread</button>
			</form>
			<a class="mx-2 action btn btn-link" href="mailto:<?= $messageObj->email; ?>">Reply</a>
			<form action="/contact/read/<?= $messageObj->id; ?>" method="post" class="d-inline">
				<input type="hidden" name="_method" value="DELETE">
				<button class="action btn btn-link">Delete</button>
			</form>
		</div>
	</div>
	<?php endif; ?>
	<!-- Message read goes here -->
	<?php if (!empty($messageObj)) : ?>
		<article>
			<h3>
				<?= $messageObj->subject; ?>
			</h3>
			<h4 class="small text-muted"><?= $messageObj->email; ?></h4>
			<section>
				<?= $messageObj->body; ?>
			</section>
		</article>
	<?php endif; ?>
</div>
</div>
<script>
	if (messageId) {
		document.querySelector('.message-container[data-id="' + messageId + '"]').classList.add('active');
	}
</script>

