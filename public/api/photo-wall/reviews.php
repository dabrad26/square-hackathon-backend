<?php include 'helpers.php'; ?>

<?php
	$method = get_method();
	$data = get_request_data();

  function remove_empty_string($var)
  {
    return $var != "";
  }

	if ($method === 'GET') {
    $review_results = query_database("SELECT * FROM Reviews");
    $final_results = array();

    if (mysqli_num_rows($review_results) > 0) {
      while($row = mysqli_fetch_assoc($review_results)) {
        $photo_entry = array();
        $review_id = $row['id'];
        $photo_results = query_database("SELECT * FROM Photos WHERE review_id = $review_id");

        if (mysqli_num_rows($photo_results) > 0) {
          while($photo_row = mysqli_fetch_assoc($photo_results)) {
            array_push($photo_entry, array("review_id" => strval($photo_row['review_id']), "url" => $photo_row['url'], "foods" => array_filter(explode(",", $photo_row['foods']), 'remove_empty_string')));
          }
        }

        $review_entry = array("id" => strval($row['id']), "text" => $row['text'], "photos" => $photo_entry);
        array_push($final_results, $review_entry);
      }
    }

		send_response([
			'status' => 'success',
			'data' => array_reverse($final_results)
		]);
	}

	if ($method === 'POST') {
    $text = array_key_exists('text', $data) ? $data['text'] : '';

    if (!array_key_exists('photos', $data)) {
      send_response(array(
        'code' => 422,
        'data' => 'Photos are missing'
      ), 422);

      return;
    }

    $new_review_id = query_database("INSERT INTO Reviews (text) VALUES (\"$text\")", true);
    $data["id"] = strval($new_review_id);

    foreach ($data['photos'] as &$photo) {
      $photo["review_id"] = $new_review_id;
      $url = $photo["url"];
      $unique_id = uniqid();
      $extension = explode(";base64", explode("data:image/", $url)[1])[0];
      $output_file = "images/$unique_id.$extension";
      $photo["url"] = $output_file;
      file_put_contents($output_file, file_get_contents($url));
      $foods = isset($photo["foods"]) ? implode(',', $photo["foods"]) : '';
      query_database("INSERT INTO Photos (review_id, url, foods) VALUES ($new_review_id, \"$output_file\", \"$foods\")");
    }

		send_response([
			'status' => 'success',
			'data' => $data
		]);
	}
