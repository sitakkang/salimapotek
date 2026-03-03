$(document).ready(function(){
	var url_ctrl = site_url+"admin/items/";

	var table = $('#tabel_custom').DataTable({
        "ajax": url_ctrl+'table',
        "deferRender": true,
        "order": [["0", "desc"]]
    });

	// Select Row Table
	$('#tabel_custom tbody').on('click', 'tr', function(e){
     	e.preventDefault();
    	if($(this).hasClass('actived')){
			$(this).removeClass('actived');
			$(this).addClass('actived');
        }else{
            table.$('tr.actived').removeClass('actived');
            $(this).addClass('actived');
        }
		rowId = table.row(this).id();
		leftWidht = e.pageX-50;
    	$('#popup_menu').css({left:leftWidht+"px",top:e.pageY+"px"}).show("fast", function(){
    		$("button#edit_btn").attr('data-id', rowId);
    		$("button#delete_btn").attr('data-id', rowId);
    	});
    });

    $(document).on('click', function(e){
    	if(e.target.nodeName !== "TD"){
    		$('#popup_menu').hide();
    		$('#popup_menu').removeAttr('style');
    	}
	});

	// Add Button
    $(document).on('click','#add_btn',function(e){
		e.preventDefault();
		$.ajax({
			method:"GET",
			cache:false,
			url:url_ctrl+'add'
		})
		.done(function(view) {
			$('#MyModalTitle').html('<b>Tambah Item</b>');
			$('div.modal-dialog').removeClass('modal-sm').addClass('modal-lg');
			$("div#MyModalContent").html(view);
			$("div#MyModalFooter").html('<button type="submit" class="btn btn-primary center-block" id="save_add_btn"><i class="fa fa-save"></i> Simpan</button>');
			$("div#MyModal").modal('show');
		})
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
		});
	});

	// Save Add
	$(document).on('click','#save_add_btn',function(e){
		e.preventDefault();

		var lastnum = table.data().count() + 1;
		var formData = new FormData();
		formData.append('kodeitem', $("input#kodeitem").val());
		formData.append('namaitem', $("input#namaitem").val());
		formData.append('hargasatuan', $("input#hargasatuan").val());
		formData.append('deskripsi', $("textarea#deskripsi").val());
		
		// Append file if exists
		var fileInput = document.getElementById('itemimage');
		if(fileInput.files.length > 0) {
			formData.append('itemimage', fileInput.files[0]);
		}

		$.ajax({
			method:"POST",
			url:url_ctrl+'act_add',
			cache:false,
			data: formData,
			processData: false,
			contentType: false
		})
		.done(function(result) {
			var obj = jQuery.parseJSON(result);
			if(obj.status == 1){
                notifNo(obj.notif);
			}
			if(obj.status == 2){
                $("div#MyModal").modal('hide');
            	notifYesAuto(obj.notif);
				table.row.add({
            		"DT_RowId" : obj.lastid,
            		"0" : lastnum,
				    "1" : $("input#kodeitem").val(),
				    "2" : $("input#namaitem").val(),
				    "3" : 'Rp ' + formatNumber($("input#hargasatuan").val()),
				    "4" : $("textarea#deskripsi").val().substr(0, 50) + '...'
		        }).draw(false);
			}
		})
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
		});
	});

	// Edit Button
	$(document).on('click','button#edit_btn',function(e){
		e.preventDefault();
	  	$.ajax({
			method:"GET",
			url:url_ctrl+'edit',
			cache:false,
			data:{id:$(this).attr('data-id')}
		})
		.done(function(view) {
			$('#MyModalTitle').html('<b>Ubah Item</b>');
			$('div.modal-dialog').removeClass('modal-sm').addClass('modal-lg');
			$("div#MyModalContent").html(view);
			$("div#MyModalFooter").html('<button type="submit" class="btn btn-primary center-block" id="save_edit_btn"><i class="fa fa-save"></i> Ubah</button>');
			$("div#MyModal").modal('show');
		})
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
		});
	});

    // Save Edit
	$(document).on('click','#save_edit_btn',function(e){
		e.preventDefault();
		
		var formData = new FormData();
		formData.append('id', $("input#id").val());
		formData.append('kodeitem', $("input#kodeitem").val());
		formData.append('namaitem', $("input#namaitem").val());
		formData.append('hargasatuan', $("input#hargasatuan").val());
		formData.append('deskripsi', $("textarea#deskripsi").val());
		
		// Append file if exists
		var fileInput = document.getElementById('itemimage');
		if(fileInput && fileInput.files.length > 0) {
			formData.append('itemimage', fileInput.files[0]);
		}

		$.ajax({
			method:"POST",
			url:url_ctrl+'act_edit',
			data: formData,
			processData: false,
			contentType: false
		})
		.done(function(result) {
			var obj = jQuery.parseJSON(result);
			if(obj.status == 1){
                notifNo(obj.notif);
			}
			if(obj.status == 2){
				$("div#MyModal").modal('hide');
                notifYesAuto(obj.notif);
                var temp = table.row('tr.actived').data(); 
                temp[1] = $("input#kodeitem").val();
				temp[2] = $("input#namaitem").val();
				temp[3] = 'Rp ' + formatNumber($("input#hargasatuan").val());
				temp[4] = $("textarea#deskripsi").val().substr(0, 50) + '...';
				table.row('tr.actived').data(temp).invalidate();
			}
		})
		.fail(function(res){
			alert('Error Response !');
			console.log("responseText", res.responseText);
		});
	});

	// Delete Button
	$(document).on('click','button#delete_btn',function(e){
		e.preventDefault();
		var id = $(this).attr('data-id');
		var rowData = table.row('tr.actived').data();
		var namaitem = rowData['2'];
		swal({
			title: 'Anda yakin ?',
			text: 'Item '+namaitem+' akan dihapus ?',
			type: 'question',
			showCancelButton: true,
			confirmButtonText: 'Ya, hapus !',
			cancelButtonText: 'Tidak, batalkan !'
		}).then((result) => {
			if (result.value) {
				$.ajax({
					method:"POST",
					url:url_ctrl+'act_del',
					data: {
						id:id,
						namaitem:namaitem
					}
				})
				.done(function(result) {
					var obj = jQuery.parseJSON(result);
					if(obj.status == 1){
		                notifNo(obj.notif);
					}
					if(obj.status == 2){
		                $("div#MyModal").modal('hide');
						notifYesAuto(obj.notif);
						table.row('tr.actived').remove().draw(false);
					}
				})
				.fail(function(res){
					alert('Error Response !');
					console.log("responseText", res.responseText);
				});
			}
		})
	});

	// Format number helper
	function formatNumber(num) {
		return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	}

	// Preview image
	$(document).on('change', '#itemimage', function(e) {
		var file = e.target.files[0];
		if (file) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#image_preview').html('<img src="'+e.target.result+'" class="img-thumbnail" style="max-width: 200px; margin-top: 10px;">');
			}
			reader.readAsDataURL(file);
		}
	});
});
