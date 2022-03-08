<?php
	session_start();
	include('include/config.php');
	include('include/checklogin.php');
	require_once('include/basicdb.php');
	check_login();
?>
<?php
	$bid = $conn->real_escape_string($_GET['bid']);
	$sb_info = get_single_data('payments', "inv_id = '{$bid}'");
	$st_info = get_single_data('students', "id = '{$sb_info['std_id']}'");
	$bills = get_some_data('payments', "inv_id = '{$bid}'");
?>
<!doctype html>
<html>
	<head>
		<title>Bill No: <?= $bid ?></title>
		<style>
			body * {box-sizing: border-box}
			.print-area{width:49%;padding-right: 0.8cm}
			table {width: 100%;border-collapse: collapse;font-family: 'Calibri'}
			table table {width: 100%}
			table h3, table p {margin: 0}
			table h3{font-size: 26px;margin-bottom: 1em}
			table tr td, table tr th{padding: 2px 5px;vertical-align: top}
			table tr th{background-color: #3B4E87;color: #fff}
			table.des-table tr:nth-child(2n-1){background-color: #F0F0F0}
			table tr td.bordered{border: 1px solid}
			@media print{body { margin: 1.6cm; } @page {margin:0;size: landscape}}
		</style>
	</head>
	<body>
		<div class="print-area">
			<table>
				<tr>
					<td align="left" width="60%">
						<h3>Sample Schoool</h3>
						<p>Sector: 7, Uttara, Dhaka</p>
						<p>Phone: 01956758055</p>
					</td>
					<td align="right" colspan="2" width="40%">
						<h3>Invoice</h3>
						<table>
							<tr align="right"><td>Date</td><td class="bordered"><?= date("d/m/Y", strtotime($sb_info['bill_date'])) ?></td></tr>
							<tr align="right"><td>Invoice Id</td><td class="bordered"><?= $bid ?></td></tr>
							<tr align="right"><td>Student Id</td><td class="bordered"><?= $st_info['id'] ?></td></tr>
						</table>
					</td>
				</tr>
			</table>
			<table style="margin: 1em 0">
				<tr><td><span style="padding:0 3em 0 1em; background: #ddd">Bill To</span></td></tr>
				<tr><td><?= $st_info['full_name'] ?></td></tr>
				<tr><td>Class: <?= $st_info['class_name'] ?>, Section: <?= $st_info['section_name'] ?></td></tr>
				<tr><td>Roll: <?= $st_info['roll_no'] ?></td></tr>
			</table>
			<table border="1" class="des-table">
				<tr><th>Description</th><th>Amount</th><th>Scholarship</th><th>Discount</th></tr>
			<?php
				$total = 0; $dis_total = 0; $sc_total = 0;
				while($rb = $bills->fetch_assoc()){ ?>
				<tr>
					<td><?= $rb['bill_title'] ?></td>
					<td><?= $currency.$rb['bill_amount'] ?></td>
					<td><?= $rb['scholarship'] ?>%</td>
					<td><?= $currency.$rb['discount'] ?></td>
				</tr>
			<?php
					$total += $rb['bill_amount'];
					$dis_total += $rb['discount'];
					$sc_total += ($rb['bill_amount'] * ($rb['scholarship']/100));
				}
			?>
			</table>
			<table>
				<tr align="right"><td>Total</td><td><?= $currency.$total ?></td></tr>
				<tr align="right"><td>Scholarship Amount</td><td><?= $currency.$sc_total ?></td></tr>
				<tr align="right"><td>Discount</td><td><?= $currency.$dis_total ?></td></tr>
				<tr align="right"><td>Subtotal</td><td><?= $currency. ($total-$sc_total-$dis_total) ?></td></tr>
			</table>
			<table style="margin: 1em 0;">
				<tr>
					<td width="10%">
						<img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?= urlencode($bid); ?>&choe=UTF-8" style="width: 100%" />
					</td>
					<td style="padding: 8px">
						If you have any question about this invoice...<br/>
						Please contact out school administrative office
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>