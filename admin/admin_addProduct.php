<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - Product Management</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <style>
    body {
      background-color: #98A1BC;
      margin: 0;
      min-height: 100vh;
      overflow-x: hidden;
    }
    .sidebar {
      width: 250px;
      background-color: #4A5578;
      color: white;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      padding-top: 20px;
      transition: all 0.3s;
      z-index: 1001;
    }
    .sidebar a {
      color: white;
      text-decoration: none;
      padding: 10px 15px;
      display: block;
      font-size: 1.1rem;
    }
    .sidebar a:hover {
      background-color: #3F4A6E;
    }
    .sidebar.collapsed {
      width: 70px;
      overflow: hidden;
    }
    .sidebar.collapsed a span {
      display: none;
    }
    .header {
      background-color: #5D6B8A;
      color: white;
      padding: 10px 20px;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
    }
    .main-content {
      margin-left: 250px;
      margin-top: 60px;
      padding: 20px;
      min-height: calc(100vh - 60px);
      background-color: #f8f9fa;
      transition: margin-left 0.3s;
    }
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }
      .sidebar.active {
        transform: translateX(0);
      }
      .main-content {
        margin-left: 0;
        margin-top: 60px;
      }
    }
    .toggle-btn {
      font-size: 1.5rem;
      background: none;
      border: none;
      color: white;
      cursor: pointer;
    }
    .btn-custom {
      background-color: #555879;
      color: white;
    }
    .btn-custom:hover {
      background-color: #98A1BC;
      color: #fff;
    }
    .card {
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <button class="toggle-btn" id="sidebarToggle">&#9776;</button>
    <a href="Dashboard.html"><span>Dashboard</span></a>
    <a href="admin_addProduct.php"><span>Add Product</span></a>
     <a href="admin_orderManage.php"><span>Order Management</span></a>
  </div>

  <!-- Header -->
  <div class="header">
    <h3>Admin Panel</h3>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container">

      <?php
      session_start();
      require_once('../dbConnection.php');
      
      if(isset($_SESSION['id'])){
        $user_id = $_SESSION['id'];
        try{
             $tablequery = $pdo->prepare("SELECT * FROM products WHERE added_by = :user_id");
            $tablequery->bindValue(":user_id" , $user_id);
            $tablequery->execute();
        }catch(PDOException $e){
            echo "error : " . $e;
        }
      }
      if(isset($_POST['submit']) && isset($_POST['productName']) && isset($_POST['productDescription']) && isset($_POST['productPrice']) && isset($_FILES['productImage']) && isset($_POST['stocks']) 
        && isset($_SESSION['id'])){
          
        $productName = $_POST['productName'];
        $productDescription = $_POST['productDescription'];
        $productPrice = $_POST['productPrice'];
        $productImage = $_FILES['productImage'];
        $stocks = $_POST['stocks'];
        $user_id = $_SESSION['id'];
    
        $imageName = basename($productImage['name']);
        $uploadImage = "../public/images/".time().$imageName;
        $imagepath = "public/images/".time().$imageName;

        $sended = move_uploaded_file($productImage['tmp_name'],$uploadImage);
        if(!$sended){
          echo "<div class='alert alert-danger'>File not uploaded</div>";
        }
        try{
          $query= $pdo->prepare("INSERT INTO products (name_of_product,description_of_product,price_of_product,image,stocks,added_by) 
          VALUES (:productName,:productDescription,:productPrice,:productImage,:stocks,:added_by)");
          $query->bindValue(':productName',$productName);
          $query->bindValue(':productDescription',$productDescription);
          $query->bindValue(':productPrice',$productPrice);
          $query->bindValue(':productImage',$imagepath);
          $query->bindValue(':stocks',$stocks);
          $query->bindValue(':added_by',$user_id);
          $query->execute();
          
          header('Location:admin_addProduct.php');
          exit();
         
          
        }catch(PDOException $e){
          die("error occur:".$e->getMessage());
        }
        
      }   

      
      ?>

      <!-- Page Title -->
      <div class="text-center mb-4">
        <h2 class="fw-bold" style="color:#555879;">Product Management</h2>
        <p class="text-muted">Add and manage your product inventory</p>
      </div>

      <!-- Add Product Form -->
      <div class="card p-4 mb-4">
        <h5 class="mb-3" style="color:#555879;">Add New Product</h5>
        <form action="" method="post" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="name" class="form-label">Product Name *</label>
              <input type="text" class="form-control" id="name" name="productName" required>
            </div>
            <div class="col-md-6">
              <label for="price" class="form-label">Price *</label>
              <input type="number" step="0.01" class="form-control" id="price" name="productPrice" required>
            </div>
            <div class="col-12">
              <label for="description" class="form-label">Description</label>
              <textarea class="form-control" id="description" name="productDescription" rows="3" required></textarea>
            </div>
            <div class="col-md-6">
              <label for="stocks" class="form-label">Stock Quantity *</label>
              <input type="number" class="form-control" id="stocks" name="stocks" required>
            </div>
            <div class="col-md-6">
              <label for="image" class="form-label">Product Image</label>
              <input type="file" class="form-control" id="image" name="productImage" accept="image/*" required>
            </div>
          </div>
          <div class="mt-4">
            <button type="submit" class="btn btn-custom" name="submit">
              <i class="bi bi-plus-circle"></i> Add Product
            </button>
          </div>
        </form>
      </div>
      
      <!-- Product Table -->
      <div class="card p-4">
        <h5 class="mb-3" style="color:#555879;">My Products</h5>
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th scope="col">Image</th>
                <th scope="col">Name</th>
                <th scope="col">Price</th>
                <th scope="col">Stock</th>
                <th scope="col">Description</th>
                <th scope="col">Created Date</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
            <!-- Example Row -->
             <?php
             while($row = $tablequery->fetch()){
                
                echo '  <tr data-id = ' . $row['id'] . '>          
                            <td>
                                <img src="../' . $row['image'] . '" class="img-thumbnail img" alt="Product Image" width="75">
                            </td>
                            <td class = "name">'. $row['name_of_product'] . '</td>
                            <td class = "price">$'. $row['price_of_product'] . '</td>
                            <td class = "stocks">' . $row['stocks'] . '</td>
                            <td class = "description">' . $row['description_of_product'] . '</td>
                            <td class = "time">' . $row['created_at'] . '</td>
                            <td>
                                <button class="btn btn-sm editBtn" style="background:#555879; color:white;" data-id = ' . $row['id'] . '>Edit</button>
                                <button data-id = ' . $row['id'] . ' class="btn btn-sm delete" style="background:#98A1BC; color:white;">Delete</button>
                            </td>
                        </tr>';
             }
             ?> 
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            
            <!-- Modal Header -->
            <div class="modal-header" style="background:#555879; color:white;">
              <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
              <form action = "api.php" method = "POST" id = "editform" >
                <div class="mb-3">
                  <label class="form-label">Product Name</label>
                  <input type="text" class="form-control" value="" id = "editName" name = "productName">
                </div>
                <div class="mb-3">
                  <label class="form-label">Price</label>
                  <input type="number" class="form-control" value="" id = "editPrice" name = "productPrice">
                </div>
                <div class="mb-3">
                  <label class="form-label">Stock</label>
                  <input type="number" class="form-control" value="" id = "editStocks" name = "productStocks">
                </div>
                 <div class="mb-3">
                  <label class="form-label">Description</label>
                  <textarea class="form-control" rows="3" id = editDescription value = "" name = "productDescription"></textarea>
                </div>
                <div class="mb-3">
                  <label class="form-label">Upload Image</label>
                  <input type="file" class="form-control" id = "editImage" name = "productImage">
                </div>
              </form>
            </div>

              <!-- Modal Footer -->
            <div class="modal-footer">
              <button type="button" class="btn" style="background:#98A1BC; color:white;" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn" style="background:#555879; color:white;" id = "update" form ='editform' >Save Changes</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.js"></script>
  <script>
    // document.getElementById('sidebarToggle').addEventListener('click', function() {
    //   const sidebar = document.getElementById('sidebar');
    //   sidebar.classList.toggle('active');
    // });

    $(document).ready(function(){
        let deleteBtn = $(".delete")
        deleteBtn.on("click" , function(){
            let Pid = $(this).data("id")
            let rBtn = $(this)
            console.log(Pid);
            $.ajax({
                url:'../api.php',
                method:"POST",
                data:{action:'deleteTable',productId:Pid},
                success:function(response){
                    console.log(response)
                    rBtn.closest('tr').remove()
                },
                error:function(xhr,status,error){
                    console.log(error)
                },
            })
        })
      let productId = null 
      let editBtn = $(".editBtn")
      editBtn.on('click',function(){
        let image = $(this).closest('tr').find('img').attr('src')
        console.log(image)
        let name = $(this).closest('tr').find('.name').text()
        console.log(name)
        let price = $(this).closest('tr').find('.price').text().replace(/[^0-9]/g,"")
        price = parseFloat(price)
        console.log(price)
        let stocks = $(this).closest('tr').find('.stocks').text()
        let description = $(this).closest('tr').find('.description').text()   
        productId = $(this).data('id')
        $("#editName").val(name)
        $("#editPrice").val(price)
        $("#editStocks").val(stocks)
        $("#editDescription").val(description)
        // $("#editImage").val(image)
        $("#editProductModal").modal('show')
      })
      let updateBtn = $("#update")
      updateBtn.on('click' , ()=>{
        
        let name = $('#editName').val()
        let price = $('#editPrice').val()
        let stocks = $('#editStocks').val()
        let description = $('#editDescription').val()
        let image = $('#editImage')[0].files[0]
        let formD = new FormData()
        formD.append("Pid",productId)
        formD.append("name",name)
        formD.append("price",price)
        formD.append("stocks",stocks)
        formD.append("description",description)
        if(image){
          formD.append("image",image)
        }
        
        formD.append("action",'edit')
        console.log(formD)
        $.ajax({
          url:'../api.php',
          method:'POST',
          data:formD,
          processData:false,
          contentType:false,
          dataType:"json",
          success:function(response){
            console.log(response)
            let productId = response.row.id
            let name = response.row.name
            let price = response.row.price
            let stocks = response.row.stocks
            let description = response.row.description
            
            console.log(name)
            let rowEdit = $('.editBtn[data-id="'+productId+'"]')
            console.log(rowEdit)
             rowEdit.closest('tr').find('.name').text(name) 
             rowEdit.closest('tr').find('.price').text("$"+price)
             rowEdit.closest('tr').find('.stocks').text(stocks) 
             rowEdit.closest('tr').find('.description').text(description)
             if(response.row.image){
              let imagePath = "../"+response.row.image
              rowEdit.closest('tr').find('.img').attr("src",imagePath) 
             }
             $("#editProductModal").modal('hide')
          },
          error:function(xhr,status,error){
            console.log(error)
          }

        })
      })
    })
  </script>
</body>
</html>
