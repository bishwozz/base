 
    <form class="form-group col-sm-10 uploadform" method='post' action="{{ $post_url }}" enctype="multipart/form-data">
              {!! csrf_field() !!}
        <div class="form-group col-md-6">              
            फाईल छान्नुहोस् : <input type='file' name='file_upload'  class='form-control' onchange=readUrl(this) required oninvalid="this.setCustomValidity('फाइल खाली नछोड्नुहोस्!')" oninput="this.setCustomValidity('')"><br>
            <input type='hidden' name='id' value='{{ $id }}'/>
      
            <img id="image" src="#"/> 
        </div>
        <button type="submit" class="btn btn-success" style="margin-left:15px;">अपलोड</button>
        <button type="button" class="btn btn-danger" onclick="$.fancybox.close()">रद्द</button>
    </form>
   <script>
        function readUrl(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(200);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
   </script>
<style>
    .uploadform{
        width:50%;
        margin-bottom: 12%;
    }
</style>