<?php include 'header.php' ?>
<?php include 'functions.php' ?>
<script src="ckeditor/ckeditor.js"></script>
<div style="float: left; width: 70%; border-radius: 5px; padding-left: 10px;">
		<p style="font-size: 30px; color: #924E22; font-family: QuicksandBold;">Announcements</p>
	<textarea id="edit" name="edit" style="width: 100%">74495</textarea>
	<script>
		// Replace the <textarea id="editor1"> with a CKEditor
		// instance, using default configuration.
		CKEDITOR.replace( 'edit', {height: 500} );
    </script>
</div>
</body>
</html>