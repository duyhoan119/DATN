 
  
    <form 
    action="{{ 
     route('products.save') }}"
     method="post" 
     enctype="multipart/form-data">
        @csrf   
            <label for="">Tên</label>
            <input type="text" name='name'>
        <br> 
            <label for="">Danh mục</label>
            <input type="text" name='category_id' value="1"> 
       <br> 
            <label for="">sku</label>
            <input type="text" name='sku'>
        <br> 
            <label for="">import_price</label>
            <input type="text" name='import_price' >
        <br> 
            <label for="">Giá</label>
            <input type="text" name='price' >
        <br> 
            <label for="">số lượng</label>
            <input type="text" name='quantity' >
        <br>
        <label for="">chi tiết</label>
            <input type="text" name='description'>
        <br> 
        <label for="">thời gian bảo hành</label>
            <input type="text" name='warranty_date'>
        <br>
           <label for="">Ảnh</label>
            <input type="file" name='image'>
        <br> 
            <label for="">Trạng thái</label>
            <input type="radio" name='status' value="1" checked>Kích hoạt
            <input type="radio" name='status' value="0" >Không kích hoạt
       <br> 
            <button class='btn btn-primary'>Tạo mới</button>
            <button type='reset' class='btn btn-warning'>Nhập lại</button>
        
    </form> 
