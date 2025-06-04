 

$(".departmentEdit").on("click",function(){
    let id = $(this).attr("data-id");
    let name = $(this).attr("data-name");
    $(".idedit").val(id);
    $(".nameedit").val(name);
})

$(".SaveDepartment").on("click",function(){
    let id = $(".idedit").val();
    let department_name = $(".nameedit").val();
    $.ajax({
        url: `department/edit`,
        type: 'POST',
        data: { 
            id:id,
            department_name:department_name  
        },
        dataType: 'json',
        success: function(response) {
           if(response){
               $(".name_depart[data-id="+id+"]").text(department_name);
               $(".departmentEdit[data-id=" + id + "]").attr("data-name", department_name);
               $('#exampleModal').modal('hide');
               alert("thnahf coong");

           }
        },
        error: function( error) {   
            console.error(error);
        }
    });
   
 })

 $(".deleteitem").on("click",async function(){
    let id = $(this).attr("data-id");
    let data= {     
        id:id
    }
    let deletea = await AjaxData(data, 'POST',"department/delete");
    if(deletea){
        $(".itemdepart[data-id=" + id + "]").css("display", "none");
        alert("thnahf coong");
    }


    
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


//   Promise 

//

