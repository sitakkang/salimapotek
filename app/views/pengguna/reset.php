<input type="hidden" id="reset_id" value="<?= $row->id_user ?>">

<div class="ds-form-group">
    <label>Nama Pengguna</label>
    <p style="font-size: 14px; font-weight: 600; color: var(--ds-text); margin: 2px 0 0;"><?= htmlspecialchars($row->fullname ?? '') ?></p>
</div>

<hr style="border-color: var(--ds-border); margin: 6px 0 14px;">

<div class="ds-form-group">
    <label>Password Baru <span class="text-danger">*</span></label>
    <input type="password" id="reset_password" name="password" class="form-control"
           placeholder="Min. 5 karakter" maxlength="100" autocomplete="off">
</div>

<div class="ds-form-group">
    <label>Ketik Ulang Password <span class="text-danger">*</span></label>
    <input type="password" id="reset_passconf" name="passconf" class="form-control"
           placeholder="Ulangi password" maxlength="100" autocomplete="off">
</div>
