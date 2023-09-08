<button id="uploadButton" class="btn btn-sm btn-primary float-right ml-3" style="background: rgb(52, 52, 163)"><i class="fa fa-file-excel-o"></i> Import</button>
<div style="display: none;">
  <div id="fancyboxContent">
    <form id="uploadForm" action="{{ route('import_project_excel') }}" method="post" enctype="multipart/form-data">
        @csrf
        <h4>फाइल हाल्नुहोस </h4><br>
        <input type="file" name="file" id="file">
        <input type="submit" value="अपलोड">
    </form>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $("#uploadButton").click(function() {
            $.fancybox.open({
            src: "#fancyboxContent",
            type: "inline",
            opts: {
                afterShow: function() {
                // Custom code to handle file upload or perform additional actions
                }
            }
            });
        });
    });
</script>