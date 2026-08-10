function notifNoAuto(data){
	swal({
		type: 'error',
		title: 'Warning !',
		html: data,
		showConfirmButton: false,
		timer: 2000
	})
};

function notifYesAuto(data){
	swal({
		type: 'success',
		title: 'Success',
		html: data,
		showConfirmButton: false,
		timer: 2000
	})
};

function notifNo(data){
	swal({
		type: 'error',
		title: 'Warning !',
		html: data
	})
};

function notifYes(data){
	swal({
		type: 'success',
		title: 'Success',
		html: data
	})
};

function notifCancle(data){
	swal({
		type: 'warning',
		title: 'Canceled',
		text: data,
		showConfirmButton: false,
		timer: 2000
	})
};

function loadingShow() {
	$('#loading').show();
}

function loadingHide() {
	$('#loading').hide();
}

$(document).ready(function(){
	// Setting Modal and Sweet Alert 2
	$("div#MyModal").on('shown.bs.modal',function(e){
		e.preventDefault();
		$('body').removeAttr('style');
	});
	$("div#MyModal").on('hidden.bs.modal',function(e){
		e.preventDefault();
		$('h5#MyModalTitle').empty();
		$("div#MyModalContent").empty();
		$("div#MyModalFooter").empty();
		$('div.modal-dialog').removeClass('modal-lg');
		$('div.modal-dialog').removeClass('modal-sm');
	});

	// ===== Fix dropdown Chosen terpotong oleh .modal-body (overflow-y:auto) =====
	// Jika select Chosen berada di baris terbawah form (mis. "Dokter Pemeriksa"),
	// dropdown-nya (position:absolute) terpotong oleh batas body modal & tertutup
	// footer. Solusi (sesuai komentar di design-system.css): saat dropdown terbuka
	// di dalam modal, ubah ke position:fixed mengikuti posisi container di viewport
	// agar dirender di atas footer, dan reposisi saat body modal discroll.
	// Catatan: event Chosen dipicu pada <select> asli, bukan pada .chosen-container.
	$(document).on('chosen:showing_dropdown', '#MyModal .autocomplete', function () {
		var $sel = $(this);
		var $c = $sel.next('.chosen-container');
		if ($c.length === 0) $c = $sel.closest('.ds-form-group').find('.chosen-container');
		if ($c.length === 0) return;

		var $drop = $c.find('.chosen-drop');
		var $body = $sel.closest('.modal-body');

		var reposition = function () {
			var r = $c[0].getBoundingClientRect();
			var vh = window.innerHeight;
			var dh = $drop[0].offsetHeight;
			var top = r.bottom + 1;
			// Jika tidak muat di bawah (bisa terpotong viewport), buka ke atas container
			if (top + dh > vh - 8) top = Math.max(8, r.top - dh - 1);
			$drop.css({
				position: 'fixed',
				top: top + 'px',
				left: r.left + 'px',
				width: r.width + 'px',
			});
		};
		reposition();
		// Jalankan ulang setelah dropdown selesai dirender agar tinggi valid
		setTimeout(reposition, 0);
		$body.on('scroll.chosenDropFix', reposition);
	});

	$(document).on('chosen:hiding_dropdown', '#MyModal .autocomplete', function () {
		var $sel = $(this);
		var $c = $sel.next('.chosen-container');
		if ($c.length === 0) $c = $sel.closest('.ds-form-group').find('.chosen-container');
		if ($c.length === 0) return;

		$c.find('.chosen-drop').css({
			position: '',
			top: '',
			left: '',
			width: '',
		});
		$sel.closest('.modal-body').off('scroll.chosenDropFix');
	});
});