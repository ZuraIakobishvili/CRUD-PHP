<?php 

$pdo = new PDO('mysql:host=localhost;dbname=products_crud','root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo randomString(8).'<br>';
echo randomString(8).'<br>';
echo randomString(8).'<br>';




$errors = [];
$title = '';
$price = '';
$description = '' ;
 
if($_SERVER['REQUEST_METHOD'] === 'POST') {
   

   

$title = $_POST['title'];
$price = $_POST['price'];
$description = $_POST['description'];

$image = $_FILES['image'] ?? null;
$imagePath = '';
if(!is_dir('images')) {
    mkdir('images');
}
if($image) {
    $imagePath = 'images/' . randomString(8) . '/' .$image['name'];
    mkdir(dirname($imagePath));
    move_uploaded_file($image['tmp_name'], $imagePath);
}


if(!$title) {
    $errors[] = 'Product title is required' ;
}

if(!$price) {
    $errors[] = 'Product price is required' ;
}

if(empty($errors)){
    $statement = $pdo->prepare("INSERT INTO products (title, image , description, price, create_date)
    VALUES(:title, :image, :description, :price, :date)");

        $statement->bindValue(':title', $title);
        $statement->bindValue(':image', $imagePath);
        $statement->bindValue(':description', $description);
        $statement->bindValue(':price', $price);
        $statement->bindValue(':date', date('Y-m-h H:i:s'));
        $statement->execute();
        header ('Location: index.php');
    }

}

function randomString($n) { // ეს ფუნქცია ქმნის სთრინგს , რომელიც შედგება  რანდომული სიმბოლოებისგან
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' ;
    $str = '' ;
    for($i = 0; $i<$n; $i++ ) {
        $index = rand(0, strlen($characters)-1);
        $str .= $characters[$index] ;
    }
    return $str;
}

// $pdo->exec("INSERT INTO products (title, image , description, price, create_date)
// VALUES('$title', '', '$description', '$price', '".date('Y-m-d H:i:s')."')");



?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <h1>Create new product</h1>

    <?php if(!empty($errors)) : ?>
    <div class="alert alert-danger">
        <?php foreach($errors as $error) : ?>
            <div><?php echo $error ?></div>
            <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form method="post" action="create.php" enctype="multipart/form-data">
        <div class="form-group">
            <label>Product Image</label>   <br>
            <input type="file" name="image" > 
        </div>
        <div class="form-group">
            <label>Product Title</label>   
            <input type="text" class="form-control" name="title" value = "<?php echo $title ?>"> 
        </div>
        <div class="form-group">
            <label>Product Description</label>   
            <textarea class="form-control" name="description"><?php echo $description ?></textarea>
        </div>
        <div class="form-group">
            <label>Product Price</label>   
            <input type="number" step=".01" class="form-control" name="price" value = "<?php echo $price ?>"> 
        </div>
        
        <button type="submit" class="btn btn-primary">Submit</button>
        
</form>
  
  </tbody>
</table>
</html>
