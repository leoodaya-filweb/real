<?php

use app\helpers\App;
use app\helpers\Url;
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<title>Certificate Verification Result</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="<?= Url::image(App::setting('image')->favicon, ['w' => 16]) ?>" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<style type="text/css">
		body {
			font-family: Arial, sans-serif;
			background-color: #f2f2f2;
			margin: 0;
			padding: 0;
		}

		.container {
			max-width: 500px;
			margin: 50px auto;
			background-color: #fff;
			padding: 30px;
			border-radius: 5px;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
		}

		h1, h2 {
			text-align: center;
			margin-bottom: 20px;
			color: #333;
		}

		.success {
			text-align: center;
		}

		.success img {
			width: 100px;
			margin-bottom: 20px;
		}

		.success p {
			font-size: 20px;
			color: #4CAF50;
		}

		.document-details {
			margin-top: 30px;
		}

		.document-details ul {
			list-style-type: none;
			padding: 0;
		}

		.document-details ul li {
			font-size: 18px;
			color: #333;
			margin-bottom: 10px;
		}

		.document-details ul li strong {
			font-weight: bold;
		}
		.svg-box {
			margin: 0 auto;
			max-width: 150px;
		}

		/* Media Queries */
		@media (max-width: 600px) {
			.container {
				max-width: 100%;
				margin: 20px;
				padding: 20px;
			}

			h1 {
				font-size: 24px;
			}

			h2 {
				font-size: 20px;
			}

			.success img {
				width: 80px;
				margin-bottom: 10px;
			}

			.success p {
				font-size: 18px;
			}

			.document-details ul li {
				font-size: 16px;
			}
		}


</style>
</head>
<body>
	<div class="container">
		<h1>Verification Result</h1>
		<div class="success">
			<div class="svg-box">
				<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M23 1v18h-3v-1h2V2H2v16h8v1H1V1zm-7 2H8v1h8zm-2 3V5h-4v1zm-7 5H3v1h4zm0 2H3v1h4zm-4 3h2v-1H3zm14-3a2 2 0 1 1-2-2 2.002 2.002 0 0 1 2 2zm-1 0a1 1 0 1 0-1 1 1.001 1.001 0 0 0 1-1zm.002-4.293a.965.965 0 0 0 1.32.55 1.08 1.08 0 0 1 1.213.207 1.066 1.066 0 0 1 .21 1.21.966.966 0 0 0 .548 1.324 1.064 1.064 0 0 1 0 2.004.965.965 0 0 0-.549 1.323A1.05 1.05 0 0 1 18 16.816v7.046l-3-2.538-3 2.538v-7.046a1.05 1.05 0 0 1-.744-1.49.965.965 0 0 0-.549-1.324 1.064 1.064 0 0 1 0-2.004.966.966 0 0 0 .549-1.324 1.066 1.066 0 0 1 .209-1.21 1.08 1.08 0 0 1 1.212-.206.965.965 0 0 0 1.32-.551 1.064 1.064 0 0 1 2.005 0zm.998 13v-5.04a.93.93 0 0 0-.998.625 1.064 1.064 0 0 1-2.004 0 .93.93 0 0 0-.998-.625v5.039l2-1.692zm-1.94-4.749a1.967 1.967 0 0 1 1.853-1.308 2.12 2.12 0 0 1 .87.197l.058-.091a1.964 1.964 0 0 1 1.116-2.695v-.122a1.966 1.966 0 0 1-1.116-2.695l-.087-.084a1.965 1.965 0 0 1-2.694-1.117h-.12a1.965 1.965 0 0 1-2.694 1.117l-.087.084a1.966 1.966 0 0 1-1.116 2.695v.122a1.964 1.964 0 0 1 1.116 2.695l.058.09a2.12 2.12 0 0 1 .87-.196 1.967 1.967 0 0 1 1.853 1.308L15 17z"></path><path fill="none" d="M0 0h24v24H0z"></path></g></svg>
			</div>
			<p>Certificate is genuine</p>
		</div>
		<div class="document-details">
			<hr>
			<h2>Document Details</h2>
			<ul>
				<li><strong>Name:</strong> <?= ucwords(strtolower($model->memberFullname)) ?></li>
				<li><strong>Date:</strong> <?= date('F d, Y', strtotime($model->createdAt)) ?></li>
				<li><strong>Type:</strong> <?= $model->transactionType['label'] ?? 'N/A' ?></li>
				<li><strong>Issuer:</strong> <?= ucwords(strtolower($model->createdByName)) ?></li>
			</ul>
		</div>
	</div>
</body>
</html>
