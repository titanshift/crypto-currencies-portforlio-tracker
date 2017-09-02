<?php
// Author : titanshift
// 09/02/2017
// access url  : /index.php?key=1d5ab8z5d6aa2f0c
   require_once('config.php');

// your can find the coins id here : https://api.coinmarketcap.com/v1/ticker/

?>
<!doctype html>
<html>
	<head>
		<title>Mycoins</title>
		<style>
			body{
			width:1115px;
			margin:auto;
			}
			
			table {
				border-collapse: collapse;
			}
			td, th {
				text-align: center;
				border: 1px solid #000;
				padding: 5px;
				background-color: #f5f5f5;
			}
			.green{
				color:green;
			}
			.red{
				color:red;
			}
			.blue {
				color: #0a2d94;
				font-weight: bold;
				text-transform: uppercase;
				background-color: #e8e8e8;
			}
		</style>
	</head>
<body>
<?php 
if(!isset($_GET['key'])&& $_GET['key']!=$key){
die('aie aie aie ! , your dont have permissions to access this page');
}
$currentTime = time();
$time = time();
echo date('d/m/Y H:i:s', $time);
$time+=150;

$file = json_decode(file_get_contents("marketcap.dat"),true);


if(isset($file["time"]) && $file["time"]>$currentTime){

	$thedata = $file;
	

}else{

	$data = file_get_contents("https://api.coinmarketcap.com/v1/ticker/");
	$data = json_decode($data,true);
	$thedata= ["time"=>$time,"data"=>$data];
	$tmp_data= json_encode($thedata);
	file_put_contents('marketcap.dat', $tmp_data);

}







$total_usd = 0;
$total_btc = 0;

foreach($currencies as & $currency){
	foreach($thedata['data'] as $curdata){
		
		if($curdata['id']==$currency['id']){
			$currency['value_usd'] = $curdata['price_usd']*$currency['amount'];
			$currency['value_btc'] = $curdata['price_btc']*$currency['amount'];
			$currency["percent_change_1h"]=$curdata["percent_change_1h"].'%'; 
    		$currency["percent_change_24h"]=$curdata["percent_change_24h"].'%'; 
    		$currency["percent_change_7d"]=$curdata["percent_change_7d"].'%';
		}

	}

	$total_usd +=$currency['value_usd'];
	$total_btc +=$currency['value_btc'];
	$currency['amount'] = number_format($currency['amount'],3);
	$currency['value_btc'] = number_format($currency['value_btc'],3);
	$currency['value_usd'] = number_format($currency['value_usd'],3);

}

echo "<br>";
echo "Total btc: ".number_format($total_btc,3);
echo "<br>";
echo "Total usd: ".number_format($total_usd,3);
echo "<br>";
echo "Buying value on 31/08/2017";

?>

<?php if (count($currencies) > 0): ?>
<table>
  <thead>
    <tr>
      <th class="blue">Crypto</th>
      <th class="blue">Amount</th>
	  <th class="blue">Buying Value $ (US)</th>
	  <th class="blue">Buying Value BTC</th>
      <th class="blue">Value $ (US)</th>
      <th class="blue">Value in BTC</th>
      <th class="blue">Change 1H</th>
      <th class="blue">Change 24H</th>
      <th class="blue">Change 7D</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($currencies as $row): array_map('htmlentities', $row);  ?>
    <tr>
        <td><?= $row['id'] ?></td>
		<td><?= $row['amount'] ?></td>
		<td><?= $row['busd'] ?></td>
		<td><?= $row['bbtc'] ?></td>
		<td><?= $row['value_usd'] ?></td>
		<td><?= $row['value_btc'] ?></td>
		<td <?php if($row['percent_change_1h'][0]=='-'): echo 'class="red"'; else: echo 'class="green"'; endif;  ?>><?= $row['percent_change_1h'] ?></td>
		<td <?php if($row['percent_change_24h'][0]=='-'): echo 'class="red"'; else: echo 'class="green"'; endif;  ?>><?= $row['percent_change_24h'] ?></td>
		<td <?php if($row['percent_change_7d'][0]=='-'): echo 'class="red"'; else: echo 'class="green"'; endif;  ?>><?= $row['percent_change_7d'] ?></td>
    </tr>
<?php  endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

</body>
