<?php 

session_start();

include "connect.php";
include "item.php";

if (isset($_GET['id'])) 
{
$result=mysqli_query($con,"SELECT *FROM product where id=".$_GET['id']);
$product=mysqli_fetch_object($result);
$item=new Item();
$item->id=$product->id;
$item->name=$product->name;
$item->price=$product->price;
$item->quantity=1;
$index=-1;
if (isset($_SESSION['cart'])) 
{
	$cart=unserialize(serialize($_SESSION['cart']));
	for ($i=0; $i <count($cart) ; $i++) 
	{ 
		if ($cart[$i]->id==$_GET['id']) 
		{
			$index=$i;
			break;
		}
	}

	if ($index==-1) 
	{
		$_SESSION['cart'][]=$item;
	}
	else
	{
		$cart[$index]->quantity++;
		$_SESSION['cart']=$cart;
	}
}


if (isset($_GET['index'])) 
{
	$cart=unserialize(serialize($_SESSION['cart']));
	unset($cart[$_GET['index']]);
	$cart=array_values($cart);
	$_SESSION['cart']=$cart;
}

?>

<table cellpadding="2" cellspacing="2" border="1">
	<tr>
		<th>Option</th>
		<th>Id</th>
		<th>Name</th>
		<th>Price</th>
		<th>Quantity</th>
		<th>Sub-Total</th>
	</tr>
<?php
$cart=unserialize(serialize($_SESSION['cart']));
$s=0;
$index=0;
for ($i=0; $i <count($cart) ; $i++) 
{ 
	$s+=$cart[$i]->price*$cart[$i]->quantity;

?>
<tr>
	<td><a href="cart.php?index=<?php echo $index; ?>" onclick="return confirm('Are You Sure?')">Delete</a></td>
	<td><?php echo $cart[$i]->id;?></td>
	<td><?php echo $cart[$i]->name;?></td>
	<td><?php echo $cart[$i]->price;?></td>
	<td><?php echo $cart[$i]->quantity;?></td>
	<td><?php echo $cart[$i]->price*$cart[$i]->quantity;?></td>
</tr>
<?php
$index ++;
}
?>
<tr>
	<td colspan="5" align="right">Sum</td>
	<td align="left"><?php echo $s; ?></td>
</tr>
</table>
<br>
<a href="index.php">Continue Shopping</a>