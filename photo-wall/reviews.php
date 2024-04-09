<?php include 'helpers.php'; ?>

<?php
	$method = get_method();
	$data = get_request_data();

	if ($method === 'GET') {
		send_response([
			'status' => 'success',
			'message' => 'TODO: Get all reviews',
		]);
	}

	if ($method === 'POST') {
		send_response([
			'status' => 'success',
			'message' => 'TODO: Create a Review',
		]);
	}

	send_response(array(
		'code' => 405,
		'message' => 'HTTP Method not allowed'
	), 405);
