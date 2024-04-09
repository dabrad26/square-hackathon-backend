<?php include 'helpers.php'; ?>

<?php
	$method = get_method();
	$data = get_request_data();

	if ($method === 'GET') {
		send_response([
			'status' => 'success',
			'data' => 'TODO: Get all reviews',
		]);
	}

	if ($method === 'POST') {
		send_response([
			'status' => 'success',
			'data' => 'TODO: Create a Review',
		]);
	}

	send_response(array(
		'code' => 405,
		'data' => 'HTTP Method not allowed'
	), 405);
