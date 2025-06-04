<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
</head>
<body>
    <h2>Đăng nhập</h2>
    <form method="POST" action="login.php">
        <label for="username">Tên đăng nhập:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">Mật khẩu:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <button class="submit" type="button">Đăng nhập</button>
        <button class="logout" type="button">dang xuat</button>
    </form>
</body>


</html>


<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script>
$(".logout").on("click",function(){
    
    
       localStorage.removeItem("user");
 

 
 
 
 })
$(".submit").on("click",function(){
    
   let usser =$("#username").val()
   let pass= $("#password").val();
   let userlogin={
            id:1,
            role:"1",
            time:   "123456"
        }
      
      localStorage.setItem("user",JSON.stringify(userlogin));


    console.log(userlogin);
//    let data = await AjaxData(dataa, 'POST',"department/delete");
//     if(data){
       
       
//     }
//     else{

//     }



})


async function AjaxData(data, type,url) {
return new Promise((resolve, reject) => {
        $.ajax({
            url: url,
            dataType: 'json',
            type: type,
            cache: false,
            data: data,
            success: function(response) {
                resolve(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                reject({
                    status: jqXHR.status,
                    statusText: textStatus,
                    responseText: jqXHR.responseText,
                    error: errorThrown
                });
            }

        });
    })
} 
</script>

