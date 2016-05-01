		<hr>
		<h4>Add Row</h2>
		<form method="post">
		<input type="hidden" name="action" value="add">
			<ul>
				<li>
					Item Location ID:
				</li>
				<li>
					<input type="number" name="location_id" style="width: 250px;">
				</li>
				<li>
					Post Author:
				</li>
				<li>
					<input type="number" name="post_author" style="width: 250px;">
				</li>
				<li>
					Posting Date:
				</li>
				<li>
					<input type="date" name="post_date" style="width: 250px;">
				</li>
				<li>
					Book Title:
				</li>
				<li>
					<input type="text" name="book_title" style="width: 250px;">
				</li>
				<li>
					Book Author:
				</li>
				<li>
					<input type="text" name="book_author" style="width: 250px;">
				</li>
				<li>
					Book Publisher:
				</li>
				<li>
					<input type="text" name="book_publisher" style="width: 250px;">
				</li>
				<li>
					Book Published:
				</li>
				<li>
					<input type="date" name="book_published" style="width: 250px;">
				</li>
				<li>
					Book Edition:
				</li>
				<li>
					<input type="text" name="book_edition" style="width: 250px;">
				</li>
				<li>
					Book Genre:
				</li>
				<li>
					<input type="text" name="book_genre" style="width: 250px;">
				</li>
				<li>
					Book ISBN:
				</li>
				<li>
					<input type="text" name="book_isbn" style="width: 250px;">
				</li>
				<li>
					Book Price:
				</li>
				<li>
					<input type="text" name="book_price" style="width: 250px;">
				</li>
				<li>
					Book Description:
				</li>
				<li>
					<input type="text" name="book_description" style="width: 250px;">
				</li>
				<li>
					Book Condition:
				</li>
				<li>
					<select name="book_condition">
						<option value="poor">Poor</option>
						<option value="fair">Fair</option>
						<option value="good">Good</option>
						<option value="new">New</option>
					</select>
				</li>
				<li>
					<input type="submit">
				</li>
			</ul>
		</form>
		<hr>
		<h4>Remove Row</h2>
		<form method="post">
			<input type="hidden" name="action" value="delete">
			<ul>
				<li>
					Record ID:
				</li>
				<li>
					<input type="number" name="book_id" style="width: 50px;">
					<input type="submit">
				</li>
			</ul>
		</form>
		<hr>
		<h4>Update Row</h2>
		<form method="post">
		<input type="hidden" name="action" value="update">
			<ul>
				<li>
					Book ID:
				</li>
				<li>
					<input type="number" name="book_id" style="width: 250px;">
				</li>
				<li>
					Item Location ID:
				</li>
				<li>
					<input type="number" name="location_id" style="width: 250px;">
				</li>
				<li>
					Item Owner:
				</li>
				<li>
					<input type="number" name="post_author" style="width: 250px;">
				</li>
				<li>
					Posting Date:
				</li>
				<li>
					<input type="date" name="post_date" style="width: 250px;">
				</li>
				<li>
					Book Title:
				</li>
				<li>
					<input type="text" name="book_title" style="width: 250px;">
				</li>
				<li>
					Book Author:
				</li>
				<li>
					<input type="text" name="book_author" style="width: 250px;">
				</li>
				<li>
					Book Publisher:
				</li>
				<li>
					<input type="text" name="book_publisher" style="width: 250px;">
				</li>
				<li>
					Book Published:
				</li>
				<li>
					<input type="date" name="book_published" style="width: 250px;">
				</li>
				<li>
					Book Edition:
				</li>
				<li>
					<input type="text" name="book_edition" style="width: 250px;">
				</li>
				<li>
					Book Genre:
				</li>
				<li>
					<input type="text" name="book_genre" style="width: 250px;">
				</li>
				<li>
					Book ISBN:
				</li>
				<li>
					<input type="text" name="book_isbn" style="width: 250px;">
				</li>
				<li>
					Book Price:
				</li>
				<li>
					<input type="text" name="book_price" style="width: 250px;">
				</li>
				<li>
					Book Description:
				</li>
				<li>
					<input type="text" name="book_description" style="width: 250px;">
				</li>
				<li>
					Book Condition:
				</li>
				<li>
					<select name="book_condition">
						<option value="poor">Poor</option>
						<option value="fair">Fair</option>
						<option value="good">Good</option>
						<option value="new">New</option>
					</select>
				</li>
				<li>
					<input type="submit">
				</li>
			</ul>
		</form>
	</body>
</html>